          var showPasswordButton = document.getElementById("showPasswordButton");
          showPasswordButton.addEventListener("click", togglePasswordVisibility);

            function togglePasswordVisibility() {
              var passwordInput = document.getElementById("passwordInput");
                if (passwordInput.type === "password") {
                      passwordInput.type = "text";
                      showPasswordButton.className = "fa fa-eye-slash";
                } else {
                      passwordInput.type = "password";
                      showPasswordButton.className = "fa fa-eye";
                } 
            }