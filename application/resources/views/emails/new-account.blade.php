Hi {{$name}},
<br><br>
You have a new account at <strong>{{env('APP_NAME')}}</strong>
<br><br>
Use below information to access your <strong>{{env('APP_NAME')}}</strong> account
<br><br>
Email: {{$username}}
<br>
Temporary Password: {{$password}}
<br><br>
You can sign in to <strong>{{env('APP_NAME')}}</strong> services at: {{env('APP_URL').'/login'}}
<br><br>
Thank You,
<br>
<strong>{{env('APP_NAME')}}</strong> Team