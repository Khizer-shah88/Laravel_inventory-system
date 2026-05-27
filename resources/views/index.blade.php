

@section('page_title', 'Dashboard')

@extends('layouts.app')

@section('content')
<div class="container mt-4">
  <h3>Currently Logged In Users</h3>
  <table class="table table-bordered">
      <thead>
          <tr>
              <th>Username</th>
              <th>IP Address</th>
              <th>Last Active</th>
          </tr>
      </thead>
      <tbody>
          @forelse($onlineUsers as $user)
              <tr>
                  <td>{{ $user->username }}</td>
                  <td>{{ $user->ip_address }}</td>
                  <td>{{ $user->last_activity }}</td>
              </tr>
          @empty
              <tr><td colspan="3">No users currently logged in.</td></tr>
          @endforelse
      </tbody>
  </table>
</div>
@endsection

