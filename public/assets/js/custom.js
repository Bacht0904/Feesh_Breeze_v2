
$(document).ready(function(){

    $("#reset-password-form").submit(function (e){

        let email = $('input[name="email"]').val();
        let password = $('input[name="password"]').val();
        let confirmPassword = $('input[name="password_confirmation"]').val();
        let errorMessage = "";

        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if(!emailRegex.test(email))
        {
            errorMessage += "Email không hợp lệ. <br>";

        }

        if(password.length<6)
        {
            errorMessage += "Mật khẩu phải có ít nhất 6 ký tự. <br>";
        }
        if(password != confirmPassword)
        {
            errorMessage += "Mật khẩu nhập lại không khớp. <br>";
        }

        if(errorMessage != "")
        {
            toastr.console.error(errorMessage,"Lỗi");
            
            e.preventDefault();
        }

    });
});

