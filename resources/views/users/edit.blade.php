<div class="container">
    <h2>Edit user</h2>
    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method("PATCH")
        <div class="mb-3">
            <label for="name" class="form-label">name</label>
            <input type="text" class="form-control" name="name" value="{{old("name", $user["name"])}}">
            @error("name")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="email" class="form-label">email</label>
            <input type="text" class="form-control" name="email" value="{{old("email", $user["email"])}}">
            @error("email")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="email_verified_at" class="form-label">email_verified_at</label>
            <input type="text" class="form-control" name="email_verified_at" value="{{old("email_verified_at", $user["email_verified_at"])}}">
            @error("email_verified_at")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="password" class="form-label">password</label>
            <input type="text" class="form-control" name="password" value="{{old("password", $user["password"])}}">
            @error("password")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="remember_token" class="form-label">remember_token</label>
            <input type="text" class="form-control" name="remember_token" value="{{old("remember_token", $user["remember_token"])}}">
            @error("remember_token")
                <p>{{$message}}</p>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>