<?php



namespace App\Livewire\Client\Auth;



use Livewire\Component;



class LoginOtp extends Component

{

    /**

     * Redirect to the main login page with OTP method selected.

     * This component is kept for backward compatibility.

     */

    public function mount()

    {

        return redirect()->route('client.auth.login', ['method' => 'otp']);

    }



    public function render()

    {

        return view('livewire.client.auth.login-otp')->layout('layouts.client.app-auth');

    }

}
