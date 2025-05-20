<div class="container">
<h2>users List</h2>
<a href="{{ route('users.create') }}" class="btn btn-primary mb-3">Create users</a>
<table class="table">
    <thead>
        <tr><th>name</th><th>email</th><th>email_verified_at</th><th>password</th><th>remember_token</th></tr>
    </thead>
    <tbody>
        @foreach ($users as $item)
                <tr>
                    <td>{{$item->name}}</td>
<td>{{$item->email}}</td>
<td>{{$item->email_verified_at}}</td>
<td>{{$item->password}}</td>
<td>{{$item->remember_token}}</td>
<td>
                        <a href="{{ route('users.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('users.destroy', $item->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>