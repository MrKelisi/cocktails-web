/**
 * Bascule entre le mode Connexion et Enregistrement
 * @param loginMode Vrai si on doit afficher le mode Connexion
 */
function toggleLoginMode(loginMode) {
	var listOfInputs = document.getElementById('register_fields').getElementsByTagName('input');

	isLogin = loginMode;

	if(loginMode) {
		document.getElementById('register_fields').classList.add('hidden');
		document.getElementById('login_button').classList.add('hidden');
		document.getElementById('register_button').classList.remove('hidden');
		document.getElementById('connexion_title').innerHTML = "Connexion";
		
		for(var i = 0; i < listOfInputs.length; i++) {
			listOfInputs[i].setAttribute("disabled", "");
		}
	}
	else {
		document.getElementById('register_fields').classList.remove('hidden');
		document.getElementById('register_button').classList.add('hidden');
		document.getElementById('login_button').classList.remove('hidden');
		document.getElementById('connexion_title').innerHTML = "S'inscrire";
		
		for(var i = 0; i < listOfInputs.length; i++) {
			listOfInputs[i].removeAttribute("disabled");
		}
		
		$('html, body').animate({scrollTop:340},'0');
	}
}

/**
 * Soumet le formulaire de connexion ou d'enregistrement au serveur
 */
function submitLoginForm() {
    if(isLogin) {
        $.post("ajax/login.php", {
            'login': document.getElementById("login").value,
            'password': document.getElementById("password").value
        })
        .done(function (json) {
            var data = JSON.parse(json);
            if(!showErrors(data)) {
				window.location = window.location.href;
            }
        });
    }
    else {
        $.post("ajax/user.php", {
            'login': document.getElementById("login").value,
            'password': document.getElementById("password").value,
            'gender': $('input[name="gender"]:checked').val(),
            'name': document.getElementById("name").value,
            'lastname': document.getElementById("lastname").value,
            'birthdate': document.getElementById("birthdate").value,
            'email': document.getElementById("email").value,
            'address': document.getElementById("address").value,
            'postal': document.getElementById("postal").value,
            'town': document.getElementById("town").value,
            'phone': document.getElementById("phone").value
        })
        .done(function (json) {
            var data = JSON.parse(json);
            if(!showErrors(data)) {
				window.location = window.location.href;
            }
        });
    }
}

$("#loginForm").on('submit', function (event) {
    event.preventDefault();
    submitLoginForm();
});

$("#register_button").on("click", function (event) {
    event.preventDefault();
    toggleLoginMode(false);
});

$("#login_button").on("click", function (event) {
    event.preventDefault();
    toggleLoginMode(true);
});