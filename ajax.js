// ajax.js
function loginAjax(event) {
    const username = document.getElementById("username").value; // Get the username from the form
    const password = document.getElementById("password").value; // Get the password from the form

    // Make a URL-encoded string for passing POST data:
    const data = { 'username': username, 'password': password };

    fetch("login_ajax.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: { 'content-type': 'application/json' }
        })
        .then(response => response.json())
        .then(data=>{
            if (data.success) {
                document.getElementById("message").innerHTML = "Logged in";
                window.location.replace("http://ec2-18-118-102-146.us-east-2.compute.amazonaws.com/~ryan38538/module5/group/calendar.php");
              } else {
                document.getElementById("message").innerHTML = "Login unsuccessful";
              }
        })
        .catch(err => console.error(err));
}
function registerAjax(event){
    const username = document.getElementById("username").value; // Get the username from the form
    const password = document.getElementById("password").value; // Get the password from the form
    // Make a URL-encoded string for passing POST data:
    const data = { 'username': username, 'password': password };

    fetch("register_Ajax.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: { 'content-type': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
          if(data.success){
            document.getElementById("message").innerHTML = "Registered";
          }
          else{
            document.getElementById("message").innerHTML = "Registration unsuccessful";
          }
        })
        .catch(err => console.error(err));
}

document.getElementById("login_btn").addEventListener("click", loginAjax, false); 
document.getElementById("register_btn").addEventListener("click",registerAjax,false);
