@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">

                <div class="panel-body">


                    @section('content')
                        <div class="col-md-12 col-sm-12 col-xs-12">

                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    {{ session('status') }}
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    {!! session('error') !!}
                                </div>
                            @endif

                            <!-- Content Start -->

                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#profile" aria-controls="home" role="tab" data-toggle="tab">Profile</a></li>
                                <li role="presentation"><a href="#password" aria-controls="password" role="tab" data-toggle="tab">Password</a></li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="profile">

                                    <div class="col-md-8 col-md-offset-2 well profile-section">
                                        <form class="form-horizontal" method="post" action="/admin/profile/update">
                                            {{ csrf_field() }}
                                          <div class="form-group">
                                            <label  class="col-sm-2 control-label">Name</label>
                                            <div class="col-sm-10">
                                              <input type="text" class="form-control" value="{{$userData->name}}"  placeholder="Your Name" required="" name="userName">
                                              <input type="hidden" name="userID" value="{{$userData->id}}">
                                            </div>
                                          </div>
                                          <div class="form-group">
                                            <label  class="col-sm-2 control-label">Email</label>
                                            <div class="col-sm-10">
                                              <input type="email" class="form-control" value="{{$userData->email}}"  placeholder="Your Email (it's also your user id)" required="" name="userEmail">
                                            </div>
                                          </div>

                                          <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                              <button type="submit" class="btn btn-success">Save</button>
                                            </div>
                                          </div>
                                        </form>
                                    </div>

                                </div>
                                <div role="tabpanel" class="tab-pane" id="password">

                                    <div class="col-md-8 col-md-offset-2 well profile-section">
                                        <form class="form-horizontal" method="post" action="/admin/profile/update/password">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="userID" value="{{$userData->id}}">
                                          <div class="form-group">
                                            <label  class="col-sm-2 control-label">Old Password</label>
                                            <div class="col-sm-10">
                                              <input type="password" class="form-control"  placeholder="Old password" required="" name="userOldPass">
                                            </div>
                                          </div>

                                          <div class="form-group">
                                            <label  class="col-sm-2 control-label">New Password</label>
                                            <div class="col-sm-10">
                                              <input type="password" class="form-control"  placeholder="New password" required="" name="password">
                                            </div>
                                          </div>

                                          <div class="form-group">
                                            <label  class="col-sm-2 control-label">Confirm Password</label>
                                            <div class="col-sm-10">
                                              <input type="password" class="form-control"  placeholder="Confirm password" required="" name="password_confirmation">
                                            </div>
                                          </div>

                                          <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                              <button type="submit" class="btn btn-danger">Change Password</button>
                                            </div>
                                          </div>
                                        </form>
                                    </div>

                                </div>
                            </div>

                            <!-- Content Ends -->


                        </div>
                    @stop


                    @push('scripts')
                    <script>
                        $(function(){
                          var hash = window.location.hash;
                          hash && $('ul.nav a[href="' + hash + '"]').tab('show');

                          $('.nav-tabs a').click(function (e) {
                            $(this).tab('show');
                            var scrollmem = $('body').scrollTop();
                            window.location.hash = this.hash;
                            $('html,body').scrollTop(scrollmem);
                          });
                        });
                    </script>
                    @endpush
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
