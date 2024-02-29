@extends('main')
@section('content')
<div class="container-scroller">
  <div class="container-fluid page-body-wrapper full-page-wrapper">
    <div class="content-wrapper d-flex align-items-center auth">
      <div class="row flex-grow">
        <div class="col-lg-4 mx-auto">
          <div class="auth-form-light text-left p-5">
            <div class="brand-logo " style="text-align:center">
              <img src="../../assets/images/smk8logo.jpeg">
            </div>
            <h4 style="text-align:center">SMK 8 TIK JAYAPURA</h4>
            <h4 style="text-align:center">APLIKASI UKK</h4>
            <h6 class="font-weight-light">Register untuk melanjutkan.</h6>
            @if ($errors->has('email'))
                <span class="text-danger text-left">{{ $errors->first('email') }}</span>
                @endif
            <form class="pt-3" method="POST" action="/auth/register">
              @csrf <!-- {{ csrf_field() }} -->
              <div class="form-group">
                <input type="text" name="name" class="form-control form-control-lg"  placeholder="Nama">
             
              </div>
              <div class="form-group">
                <input type="email" name="email" class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Email">
             
              </div>
              <div class="form-group">
                <input type="password" name="password" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="Password">
              </div>
              <div class="form-group">
                <input type="password" name="password_confirmation" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="Confirm Password">
              </div>
              <div class="mt-3">
                <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">SIGN IN</button>
              </div>
              <div class="my-2 d-flex justify-content-between align-items-center">
                <div class="form-check">
                  <label class="form-check-label text-muted">
                    <input type="checkbox" class="form-check-input"> Keep me signed in </label>
                </div>
                <a href="#" class="auth-link text-black">Forgot password?</a>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- content-wrapper ends -->
  </div>
  <!-- page-body-wrapper ends -->
</div>

@stop