@extends('front.layout.main')

@section('content')


<!-- Full Width Container -->
<div class="container-fluid">
  <div class="row">
      <!-- Main Container -->
      <div class="container">

          <!-- logo container -->
          <div class="row">
              <div class="col-md-offset-3 col-md-6">
                  <div class="logo-container">
                      <a href="/">
                      <img src="{{ (setting('site_logo')) ? setting('site_logo') : '/img/main-logo.png' }}" class="logo img-responsive" />
                      </a>
                      <h3 class="subtitle">SEND FILES TO ANYONE. FAST & FREE.</h3>
                  </div>
              </div>
          </div>
          <!-- logo container ends-->

          <!-- main data container -->
          <div class="row last-bottom-gap">
              <div class="col-md-2 col-xs-1"></div>
              <div class="col-md-8 col-xs-10">

                  <div class="row">
                    <ul class="list-unstyled zipfile-page-nav">
                        <li >
                          <a href="/about">ABOUT</a>
                        </li>
                        <li class="active">
                          <a href="/help">HELP</a>
                        </li>
                        <li>
                          <a href="/legal">LEGAL</a>
                        </li>
                    </ul>
                  </div>

                  <div class="row zipfile-data-container-page">

                    @if( (setting('help_page')) )
                      {!! setting('help_page') !!}
                    @else
                    <h1>Help & Support</h1>

                    <p>
                      Haivng trouble using ZipFIleMe? Submit an inquiry below or email us at hello@example.me
                    </p>

                    @endif



                    <p>
                      <form method="post" id="helpFrm" action="/help" class="zipfile-contact-form">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <input type="email" name="userEmail" id="userEmailHlp" class="form-control zfl-input-style " placeholder="Type in your email address" value="" required="">
                        </div>
                        <div class="form-group">
                            <textarea name="userMsg" class="form-control zfl-input-style textbox" rows="3" placeholder="How can we help you?" id="userMsgHlp" maxlength="900" required=""></textarea>
                        </div>

                        @if (session('status'))
                            @if (session('status') == 'ok')
                            <button type="submit" class="btn btn-lg btn-block zfl-button submitHelpMsg success">INQUIRY SENT</button>
                            @endif
                        @else
                          <button type="submit" class="btn btn-lg btn-block zfl-button submitHelpMsg">SEND INQUIRY</button>
                        @endif


                      </form>

                    </p>

                  </div>

              </div>
              <div class="col-md-2 col-xs-1"></div>
          </div>

      </div>
      <!-- Main Container ends -->
  </div>
</div>
<!-- Full Width Container -->
@endsection

@section('custome-js')
@endsection
