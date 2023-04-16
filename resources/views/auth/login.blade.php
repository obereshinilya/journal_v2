<link rel="stylesheet" href="/assets/auth_form/bootstrap.min.css" />
<link rel="stylesheet" href="/assets/auth_form/font-awesome.min.css" />
<body>
     <div class="container">
         <div class="row">

             <div class="col-md-offset-3 col-md-6">
                 <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                     @csrf
                     <div class="heading" style="font-size: 20px; height: 50px">

                         <div style="position: absolute; top: 20px; left: 0; width: 30%">
                             <img src="/assets/favicon.ico">
                         </div>

                         <div style="position:absolute; top: 20px; right: 5%; width: 75%; text-align: center">
                             Авторизация в<br>
                         журнале диспетчера
                         </div>

                     </div>

                     <div class="form-group">
                         <input type="text"  name="email" class="form-control" id="inputEmail" required autocomplete="email" autofocus placeholder="Логин или E-mail">
                         <i class="fa fa-user"></i>
                     </div>
                     <div class="form-group help">
                         <input type="password" name="password" class="form-control" id="inputPassword" placeholder="Пароль">
                         <i class="fa fa-lock"></i>
                     </div>
                     <div class="form-group" style="text-align: center">
                         <button type="submit" class="btn btn-default">Вход</button>
                     </div>
                     @if ($errors->has('email'))
                         <span style="font-size: 15px; color: #2b542c; font-weight: bolder; margin-bottom: 20px">
                         Введены неверные учетные данные!
                        </span>
                     @endif

                 </form>
             </div>
         </div><!-- /.row -->
     </div><!-- /.container -->
</body>
<style>
    /* Demo Background */
    body{background:url(/assets/img/fon_auth.png)}
    /* Form Style */
    .form-horizontal{
        background: #fff;
        padding-bottom: 20px;
        border-radius: 15px;
        text-align: center;
    }
    .container{
        position: absolute;
        width: 40%;
        height: 40%;
        top: 30%;
        left: 30%;
    }
    .form-horizontal .heading{
        display: block;
        font-size: 35px;
        font-weight: 700;
        padding: 45px 0;
        border-bottom: 1px solid #f0f0f0;
        margin-bottom: 20px;
    }
    .form-horizontal .form-group{
        padding: 0 40px;
        /*margin: 0 0 25px 0;*/
        position: relative;
    }
    .form-horizontal .form-control{
        background: #f0f0f0;
        border: none;
        border-radius: 20px;
        box-shadow: none;
        padding: 0 20px 0 45px;
        height: 40px;
        transition: all 0.3s ease 0s;
    }
    .form-horizontal .form-control:focus{
        background: #e0e0e0;
        box-shadow: none;
        outline: 0 none;
    }
    .form-horizontal .form-group i{
        position: absolute;
        top: 12px;
        left: 60px;
        font-size: 17px;
        color: #c8c8c8;
        transition : all 0.5s ease 0s;
    }
    .form-horizontal .form-control:focus + i{
        color: #00b4ef;
    }
    .form-horizontal .fa-question-circle{
        display: inline-block;
        position: absolute;
        top: 12px;
        right: 60px;
        font-size: 20px;
        color: #808080;
        transition: all 0.5s ease 0s;
    }
    .form-horizontal .fa-question-circle:hover{
        color: #000;
    }
    .form-horizontal .main-checkbox{
        float: left;
        width: 20px;
        height: 20px;
        background: #11a3fc;
        border-radius: 50%;
        position: relative;
        margin: 5px 0 0 5px;
        border: 1px solid #11a3fc;
    }
    .form-horizontal .main-checkbox label{
        width: 20px;
        height: 20px;
        position: absolute;
        top: 0;
        left: 0;
        cursor: pointer;
    }
    .form-horizontal .main-checkbox label:after{
        content: "";
        width: 10px;
        height: 5px;
        position: absolute;
        top: 5px;
        left: 4px;
        border: 3px solid #fff;
        border-top: none;
        border-right: none;
        background: transparent;
        opacity: 0;
        -webkit-transform: rotate(-45deg);
        transform: rotate(-45deg);
    }
    .form-horizontal .main-checkbox input[type=checkbox]{
        visibility: hidden;
    }
    .form-horizontal .main-checkbox input[type=checkbox]:checked + label:after{
        opacity: 1;
    }
    .form-horizontal .text{
        float: left;
        margin-left: 7px;
        line-height: 20px;
        padding-top: 5px;
        text-transform: capitalize;
    }
    .form-horizontal .btn{
        /*float: right;*/
        font-size: 14px;
        color: #fff;
        background: #00b4ef;
        border-radius: 30px;
        padding: 10px 25px;
        border: none;
        text-transform: capitalize;
        transition: all 0.5s ease 0s;
    }
    @media only screen and (max-width: 479px){
        .form-horizontal .form-group{
            padding: 0 25px;
        }
        .form-horizontal .form-group i{
            left: 45px;
        }
        .form-horizontal .btn{
            padding: 10px 20px;
        }
    }
</style>
