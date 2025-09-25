<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class SocialLoginController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback(Socialite $socialite, $provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            $email = $socialUser->getEmail();
            if (!$email) {
                return redirect('/')->withErrors(['social' => 'Facebook account has no email. Please use another method.']);
            }
            $user = User::firstOrNew(['email' => $email]);
            $providerColumn = $provider . '_provider_id';

            if (!$user->exists) {
                $user->name = $socialUser->getName();
                $user->password = Hash::make(Str::random(32));
                $user->email_verified_at = now();
                $user->uuid = (string) Str::uuid();
                $user->save();

                // ✅ Download and save avatar photo as WebP
                if ($socialUser->getAvatar()) {
                    if ($provider === 'facebook') {
                        $avatarUrl = "https://graph.facebook.com/{$socialUser->getId()}/picture?type=large";
                    } else {
                        $avatarUrl = $socialUser->getAvatar();
                    }
                    $response = Http::get($avatarUrl);

                    if ($response->ok()) {
                        $ext = 'webp';
                        $filename = date('YmdHi') . '.' . $ext;
                        $saveDir = public_path('upload/users/' . $user->id);
                        $savePath = $saveDir . '/' . $filename;

                        // Create folder if needed
                        if (!File::exists($saveDir)) {
                            File::makeDirectory($saveDir, 0777, true);
                        }

                        // Save image using Intervention as WebP
                        $manager = new ImageManager(new Driver());
                        $image = $manager->read($response->body());
                        $image->coverDown(1000, 1000)->toWebp(90)->save($savePath); // quality: 90

                        $user->photo = $filename;
                        $user->save(); // Save again with photo
                    }
                }
            }

            $user->$providerColumn = $socialUser->getId();
            $user->email_verified_at = now();
            $user->save();

            Auth::login($user, true);
            return redirect()->intended('/');
        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            return redirect('/')->withErrors(['social' => 'Login failed: Invalid state.']);
        } catch (\Exception $e) {
            return redirect('/')->withErrors(['social' => 'Login failed: ' . $e->getMessage()]);
        }
    }
}
