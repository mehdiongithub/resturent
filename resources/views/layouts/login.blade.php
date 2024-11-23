<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
body {font-family: Arial, Helvetica, sans-serif;}
form {
    border: 3px solid #f1f1f1;
    width: 50vw;
    margin: 5vh auto 0;


}

form h2{
    text-align: center
}

input[type=email], input[type=password] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

button {
  background-color: #04AA6D;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
}

button:hover {
  opacity: 0.8;
}

.cancelbtn {
  width: auto;
  padding: 10px 18px;
  background-color: #f44336;
}

.imgcontainer {
  text-align: center;
  margin: 24px 0 12px 0;
}

img.avatar {
  width: 20%;
  border-radius: 50%;
}

.container {
  padding: 16px;
}

span.psw {
  float: right;
  padding-top: 16px;
}

/* Change styles for span and cancel button on extra small screens */
@media screen and (max-width: 300px) {
  span.psw {
     display: block;
     float: none;
  }
  .cancelbtn {
     width: 100%;
  }
}
</style>
</head>
<body>



    <form id="loginForm" action="{{ route('register') }}" method="POST">
        @csrf <!-- Add this line to include CSRF token -->
        <h2>Login Form</h2>

  <div class="imgcontainer">
    <img src="{{asset('assets/auth/img/img_avatar2.png')}}" alt="Avatar" class="avatar">
  </div>

  <div class="container">
    <label for="uname"><b>Username</b></label>
    <input type="email" placeholder="Enter Username" name="email" required>

    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="password" required>
        
    <button type="submit">Login</button>
    {{-- <label>
      <input type="checkbox" checked="checked" name="remember"> Remember me
    </label> --}}
  </div>

  <div class="container" style="background-color:#f1f1f1">
    <button type="button" class="cancelbtn">Cancel</button>
    <span class="psw">Forgot <a href="#">password?</a></span>
  </div>
</form>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
    $(document).ready(function(){
        $('#loginForm').on('submit', function(e) {
            e.preventDefault();  // Prevent the form from submitting the normal way

            // Get form data
            var formData = $(this).serialize(); // Serialize the form data

            // Send AJAX request
            $.ajax({
                url: '{{ route('register') }}',  // The route for your login
                type: 'POST',                      // Method type
                data: formData,                    // Data to send
                success: function(response) {
                    // Handle the response from the server
                    if (response.success) {
                        window.location.href = response.redirect;
                        // Redirect or handle login success here
                    } else {
                        alert('Login failed: ' + response.message);
                        // Show the error message here
                    }
                },
                error: function(xhr, status, error) {
                    // Handle AJAX error
                    console.log('Error: ' + error);
                    alert('There was an error with the login request.');
                }
            });
        });
    });
</script>


</body>
</html>
