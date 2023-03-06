@extends('admin.layouts.admin_master')

@section('title_bar')
Profile
@endsection

@section('body_content')
<div class="row clearfix">
    <div class="col-lg-4 col-md-12">
        <div class="card profile-header">
            <div class="body text-center">
                <div class="profile-image mb-3">
                    <img width="180" height="180" src="{{asset('uploads/profile_photo')}}/{{Auth::guard('admin')->user()->profile_photo}}" class="rounded-circle" alt="{{Auth::guard('admin')->user()->name}}">
                </div>
                <div>
                    <h4 class="mb-0"><strong>{{Auth::guard('admin')->user()->name}}</strong></h4>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="header">
                <h2>Info</h2>
            </div>
            <div class="body">
                <small class="text-muted">Email address: </small>
                <p>{{Auth::guard('admin')->user()->email}}</p>
                <hr>
                <small class="text-muted">Phone Number: </small>
                <p>{{Auth::guard('admin')->user()->phone_number}}</p>
                <hr>
                <small class="text-muted">Gender: </small>
                <p class="m-b-0">{{Auth::guard('admin')->user()->gender}}</p>
                <hr>
                <small class="text-muted">Address: </small>
                <p>{{Auth::guard('admin')->user()->address}}</p>
                <hr>
            </div>
        </div>
    </div>

    <div class="col-lg-8 col-md-12">
        <div class="card">
            <div class="header bline">
                <h2>Basic Information</h2>
            </div>
            <div class="body">
                <form action="{{route('admin.change.profile')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <input type="file" class="form-control" name="profile_photo">
                                @error('profile_photo')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <input type="text" class="form-control" name="name" value="{{Auth::guard('admin')->user()->name}}">
                                @error('name')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <input type="text" class="form-control" name="phone_number" value="{{Auth::guard('admin')->user()->phone_number}}">
                                @error('phone_number')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <div>
                                    <label class="fancy-radio">
                                        <input name="gender" value="male" type="radio" {{(Auth::guard('admin')->user()->gender == 'male') ? 'checked' : ''}}>
                                        <span><i></i>Male</span>
                                    </label>
                                    <label class="fancy-radio">
                                        <input name="gender" value="female" type="radio" {{(Auth::guard('admin')->user()->gender == 'female') ? 'checked' : ''}}>
                                        <span><i></i>Female</span>
                                    </label>
                                    <label class="fancy-radio">
                                        <input name="gender" value="other" type="radio" {{(Auth::guard('admin')->user()->gender == 'other') ? 'checked' : ''}}>
                                        <span><i></i>Others</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <textarea name="address" class="form-control" placeholder="Address">{{Auth::guard('admin')->user()->address}}</textarea>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button> &nbsp;&nbsp;
                </form>
            </div>
        </div>

        <div class="card">
            <div class="header bline">
                <h2>Change Password</h2>
            </div>
            <div class="body">
                <form action="{{route('admin.change.password')}}" method="POST">
                    @csrf
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <input type="password" class="form-control" name="old_password" placeholder="Old Password">
                                @error('password_error')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                                @error('old_password')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" placeholder="New Password">
                                @error('password')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" >
                                @error('password_confirmation')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button> &nbsp;&nbsp;
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_script')
<script>
    $(function () {
        $('.knob').knob({
            draw: function () {
            }
        });
    });
</script>
@endsection
