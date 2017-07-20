Hi {{$name}},
<br><br>
You have a new account at <strong>{{config('constants.default.app_name')}}</strong>
<br><br>
Use below information to access your <strong>{{config('constants.default.app_name')}}</strong> account
<br><br>
Email: {{$username}}
<br>
Temporary Password: {{$password}}
<br><br>
You can sign in to <strong>{{config('constants.default.app_name')}}</strong> services at: {{env('APP_URL').'/login'}}
<br><br>
Thank You,
<br>
<strong>{{config('constants.default.app_name')}}</strong> Team