<HTML>
<HEADER>
	<meta charset="UTF-8" />
	<TITLE> Sistema Expeto Médico</TITLE>
	<link rel="stylesheet" href="css/estilo.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="js/jquery-3.1.1.js"></script>
	<script src="js/artyom.min.js"></script>
	<script src="js/artyomCommands.js"></script>

	<!--  -->


</HEADER>


<body >

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header" >
      <a class="navbar-brand" href="#" id="iniciar" name="iniciar" onclick="startContinuousArtyom();">
      Sistema Experto </a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="index.php" >Inicio</a></li>
      <li><a href="https://www.cun.es/enfermedades-tratamientos/enfermedades">Enfermedades</a></li>
      <li><a href="https://www.cun.es">Sitio web</a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li><button id="iniciar" name="iniciar" onclick="startContinuousArtyom();" type="button" class="btn btn-success btn-sm navbar-btn">Iniciar</button></li>
      <li><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
      <li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
    </ul>
  </div>
</nav>


<div class="col-xs-5">
    <textarea id="textovoz" name="textovoz" rows="8" class="form-control" placeholder="Texto introducido por Aúdio"></textarea>
    <button id="iniciar" name="iniciar" onclick="audio_text();" type="button" class="btn btn-danger btn-sm">Grabar</button>
	<button id="parar" name="parar" onclick="stopArtyom();" type="button" class="btn btn-warning btn-sm">Parar</button>
</div>

<div class="col-xs-1">
	<button type="submit" name="enviar" id="enviar" type="button" class="btn btn-success btn-sm"> Consultar </button> 
</div>

<div class="col-xs-5" align="right">
	<!--<button type="reset" onclick='textovoz.value=""'>Borrar</button> -->
	<textarea id="resultado" name="resultado" rows="8" class="form-control" placeholder="Respuesta obtenida del sistema"></textarea>
	<input type="button" id="btnLeer" value="Leer" class="btn btn-success btn-sm">
</div>			
			

</body>

<footer>
  <div id="pie">2018 &copy; Sistema Experto Médico (SEM) </div>
</div>
</footer>

<script type="text/javascript">
	function audio_text(){
		// Escribir lo que escucha.
		artyom.redirectRecognizedTextOutput(function(text,isFinal){
			var texto = $('#textovoz');
			if (isFinal) {
				texto.val(text);
			}
		});
	}

</script>

<script type="text/javascript">
	// Funciones de ARTYOM

	//Notificaciones de las funcionalidades de los botones
	/*
	$('#parar').mouseover(function() {
		artyom.say("Finalizar la aplicacion")
	});

	$('#para').mouseout(function() {
		artyom.shutUp();
	});  
	*/

	//inicializamos la libreria Artyom
	function startContinuousArtyom(){
		artyom.initialize({
			lang: "es-ES",
			continuous:true,// Reconoce 1 solo comando y para de escuchar
	        listen:true, // Iniciar !
	        debug:true, // Muestra un informe en la consola
	        speed:1 // Habla normalmente
		}).then(function(){
            	console.log("Sistema iniciado..!");
        });
	};
		
	// Parar la libreria Artyom;
	function stopArtyom(){
		artyom.fatality();// Detener cualquier instancia previa
	};



	// leer texto
	$('#btnLeer').click(function (e) {
        e.preventDefault();
        var btn = $('#btnLeer');
        if (artyom.speechSupported()) {
            btn.addClass('disabled');
            btn.attr('disabled', 'disabled');
            var text = $('#resultado').val();
            if (text) {
                var lines = $("#resultado").val().split("\n").filter(function (e) {
                    return e
                });
                var totalLines = lines.length - 1;
                for (var c = 0; c < lines.length; c++) {
                    var line = lines[c];
                    if (totalLines == c) {
                        artyom.say(line, {
	                        onEnd: function () {
                                btn.removeAttr('disabled');
                                btn.removeClass('disabled');
                            }
                        });
                    } else {
                        artyom.say(line);
                    }
                }
            }
        } else {
            alert("Your browser cannot talk !");
        }
    });

</script>


<script>
	// funcion para enviar la consulta
		$(document).ready(function(){
			$("#enviar").click(function(){

				var consulta = $("#textovoz").val();

				$.get("conexion.php", {texto_consulta:consulta}, function(datos){
					$("#resultado").html(datos);
				});

			});
		});
</script>


<script type="text/javascript">

	// Comandos por voz
	artyom.addCommands([
		{
		// saludos
		indexes:["hola","buenos días",'iniciar'],
			action: function(i){
				if (i==0) {
					artyom.say("Carlos, en que puedo ayudarte");
				}
				if (i==1) {
					artyom.say("Buen día, en que puedo ayudarte");
				}
				if (i==2) {
					artyom.say("Sistema iniciado, en que puedo ayudarte");
				}
			}
		},

		{
		// situaciones
		indexes:["estoy enfermo","tengo dolencias","me siento mal", "creo que estoy enfermo"],
			action: function(i){
				if (i==0 || i==1 || i==2 || i==3) {
					artyom.say("Podrias especificarme tus dolencias o síntomas que padeces");
				}
			}
		},

		{
		// sintomas gripe
		indexes:["tengo tos","tengo cansancio","tengo fiebre", "tengo dolor de la cabeza", "me duele la cabeza", "tos", "cansancio", "dolor de la cabeza", "fiebre"],
			action: function(i){
				if (i==0 || i==1 || i==2 || i==3 || i==4 || i==5 || i==6 || i==7 || i==8 ) {
					artyom.say("Usted podría estar padeciendo de gripe, se recomienda reposo e hidratación continua y utlizar medicamentos como zanamivir y osetalmivir. Para mayor información visite a un otorrino");
				} 				
				if (i==0 && i==1 || i==0 && i==2 || i==0 && i==3 || i==0 && i==4 || i==0 && i==6 || i==0 && i==7 || i==0 && i==8 ) {
					artyom.say("Usted podría estar padeciendo de gripe, se recomienda reposo e hidratación continua y utlizar medicamentos como zanamivir y osetalmivir. Para mayor información visite a un otorrino");
				}
				if (i==1 && i==0 || i==1 && i==2 || i==1 && i==3 || i==1 && i==4 || i==1 && i==5 || i==1 && i==7 || i==1 && i==8 ) {
					artyom.say("Usted podría estar padeciendo de gripe, se recomienda reposo e hidratación continua y utlizar medicamentos como zanamivir y osetalmivir. Para mayor información visite a un otorrino");
				}
				if (i==2 && i==0 || i==2 && i==1 || i==2 && i==3 || i==2 && i==4 || i==2 && i==5 || i==2 && i==6 || i==2 && i==7 ) {
					artyom.say("Usted podría estar padeciendo de gripe, se recomienda reposo e hidratación continua y utlizar medicamentos como zanamivir y osetalmivir. Para mayor información visite a un otorrino");
				}
				if (i==3 && i==0 || i==3 && i==1 || i==3 && i==2 || i==3 && i==5 || i==3 && i==6 || i==3 && i==8 ) {
					artyom.say("Usted podría estar padeciendo de gripe, se recomienda reposo e hidratación continua y utlizar medicamentos como zanamivir y osetalmivir. Para mayor información visite a un otorrino");
				}
				if (i==4 && i==0 || i==4 && i==1 || i==4 && i==2 || i==4 && i==5 || i==4 && i==6 || i==4 && i==8 ) {
					artyom.say("Usted podría estar padeciendo de gripe, se recomienda reposo e hidratación continua y utlizar medicamentos como zanamivir y osetalmivir. Para mayor información visite a un otorrino");
				}

				if (i==5 && i==6 || i==5 && i==7 || i==5 && i==8 || i==6 && i==5 || i==6 && i==7 || i==6 && i==8 || i==7 && i==5 || i==7 && i==6 || i==7 && i==8 || i==8 && i==5 || i==8 && i==6 || i==8 && i==7 ) {
					artyom.say("Usted podría estar padeciendo de gripe, se recomienda reposo e hidratación continua y utlizar medicamentos como zanamivir y osetalmivir. Para mayor información visite a un otorrino");
				}

			}
		},

		{
		// sintomas hepatitis
		indexes:["tengo nauseas","me dan nauseas","tengo diarrea","tengo ictericia", "nauseas", "diarrea", "ictericia"],
			action: function(i){
				if (i==0 || i==1 || i==2 || i==3 || i==4 || i==5 || i==6) {
					artyom.say("Usted podría estar padeciendo de hepatitis, se recomienda utilizar estos fármacos interferón alfa, lamivudina, adefovir dipivoxil. Para mayor información visite a un Especialista en Endocrinología");
				}
				if (i==0 && i==2 || i==0 && i==3 || i==0 && i==5 || i==0 && i==6 ) {
					artyom.say("Usted podría estar padeciendo de hepatitis, se recomienda utilizar estos fármacos interferón alfa, lamivudina, adefovir dipivoxil. Para mayor información visite a un Especialista en Endocrinología");
				}
				if (i==1 && i==2 || i==1 && i==3 || i==1 && i==5 || i==1 && i==6 ) {
					artyom.say("Usted podría estar padeciendo de hepatitis, se recomienda utilizar estos fármacos interferón alfa, lamivudina, adefovir dipivoxil. Para mayor información visite a un Especialista en Endocrinología");
				}
				if (i==2 && i==0 || i==2 && i==1 || i==2 && i==3 || i==2 && i==4 || i==2 && i==6 ) {
					artyom.say("Usted podría estar padeciendo de hepatitis, se recomienda utilizar estos fármacos interferón alfa, lamivudina, adefovir dipivoxil. Para mayor información visite a un Especialista en Endocrinología");
				}
				if (i==3 && i==0 || i==3 && i==1 || i==3 && i==2 || i==3 && i==4 || i==3 && i==5 ) {
					artyom.say("Usted podría estar padeciendo de hepatitis, se recomienda utilizar estos fármacos interferón alfa, lamivudina, adefovir dipivoxil. Para mayor información visite a un Especialista en Endocrinología");
				}
				if (i==4 && i==5 || i==4 && i==6 || i==5 && i==4 || i==5 && i==6 || i==6 && i==5 || i==6 && i==4 || i==6 && i==5 && i==4 || i==4 && i==5 && i==6 || i==5 && i==4 && i==6 || i==6 && i==4 && i==5 ) {
					artyom.say("Usted podría estar padeciendo de hepatitis, se recomienda utilizar estos fármacos interferón alfa, lamivudina, adefovir dipivoxil. Para mayor información visite a un Especialista en Endocrinología");
				}
			}
		},

		{
		// sintomas rubeola
		indexes:["tengo jaquecas","me dan jaquecas","tengo secreción en la piel", "jaquecas", "secreción en la piel"],
			action: function(i){
				if (i==0 || i==1 || i==2 || i==3) {
					artyom.say("Usted podría estar padeciendo de rubeola, ya que no existe un tratamiento específico, este deberá ser llevado por un especialista, en base a más análisis. Para mayor información visite a un Especialista en Medicina General");
				}
				if (i==0 && i==2 || i==0 && i==4 || i==1 && i==2 || i==1 && i==4 || i==2 && i==0 || i==2 && i==1 || i==2 && i==3 || i==3 && i==2 || i==3 && i==4 || i==4 && i==0 || i==4 && i==1 || i==1 && i==3 ) {
					artyom.say("Usted podría estar padeciendo de rubeola, ya que no existe un tratamiento específico, este deberá ser llevado por un especialista, en base a más análisis. Para mayor información visite a un Especialista en Medicina General");
				}
				if (i==3 && i==4 || i==4 && i==3 ) {
					artyom.say("Usted podría estar padeciendo de rubeola, ya que no existe un tratamiento específico, este deberá ser llevado por un especialista, en base a más análisis. Para mayor información visite a un Especialista en Medicina General");
				}

			}
		},


		{
		// sintomas malaria
		indexes:["tengo escalofrios","me dan escalofrios","tengo dolor muscular", "me duelen los músculos", "me duele la espalda", "tengo dolor de espalda", "escalofrios", "dolor muscular", "dolor de espalda"],
			action: function(i){
				if (i==0 || i==1 || i==2 || i==3 || i==4 || i==5 || i==6 || i==7 || i==8 ) {
					artyom.say("Usted podría estar padeciendo malaria, actualmente no existe una vacuna contra esta enfermedad, el taratamiento deberá ser llevado por un especialista segun su edad, zona de afección, gravedad del malestar, casos de enbarazos, si aun no ha contraido esta enfermedad puede prevenirla mediante la aplicación de Cloroquina, Doxiciclina, Mefloquina o Primaquina. Para mayor información visite a un Especialista en Medicina General");
				}
				if (i==0 && i==2 || i==0 && i==3 || i==0 && i==4 || i==0 && i==5 || i==0 && i==7 || i==0 && i==8 || i==1 && i==2 || i==1 && i==3 || i==1 && i==4 || i==1 && i==5 || i==1 && i==7 || i==1 && i==8 || i==2 && i==0 || i==2 && i==1 || i==2 && i==4 || i==2 && i==5 || i==2 && i==6 || i==0 && i==8 || i==3 && i==0 || i==3 && i==1 || i==3 && i==4 || i==3 && i==5 || i==3 && i==6 || i==3 && i==8 || i==4 && i==0 || i==4 && i==1 || i==4 && i==2 || i==4 && i==3 || i==4 && i==6 || i==4 && i==7 || i==5 && i==0 || i==5 && i==1 || i==5 && i==2 || i==5 && i==3 || i==5 && i==6 || i==5 && i==7 || i==6 && i==7 || i==6 && i==8 || i==7 && i==6 || i==7 && i==8 || i==8 && i==6 || i==8 && i==7 ) {
					artyom.say("Usted podría estar padeciendo malaria, actualmente no existe una vacuna contra esta enfermedad, el taratamiento deberá ser llevado por un especialista segun su edad, zona de afección, gravedad del malestar, casos de enbarazos, si aun no ha contraido esta enfermedad puede prevenirla mediante la aplicación de Cloroquina, Doxiciclina, Mefloquina o Primaquina. Para mayor información visite a un Especialista en Medicina General");
				}
				if ( i==6 && i==7 && i==8 || i==7 && i==6 && i==8 || i==8 && i==7 && i==6 || i==8 && i==6 && i==7 ) {
					artyom.say("Usted podría estar padeciendo malaria, actualmente no existe una vacuna contra esta enfermedad, el taratamiento deberá ser llevado por un especialista segun su edad, zona de afección, gravedad del malestar, casos de enbarazos, si aun no ha contraido esta enfermedad puede prevenirla mediante la aplicación de Cloroquina, Doxiciclina, Mefloquina o Primaquina. Para mayor información visite a un Especialista en Medicina General");
				}

				
			}
		},

		{
		// sintomas tuberculosis
		indexes:["tengo afeccón extrapulmonar", "tengo tos productiva","tengo astenia", "afeccón extrapulmonar", "tos productiva", "astenia"],
			action: function(i){
				if (i==0 && i==1 || i==0 && i==1 || i==0 && i==2 || i==0 && i==4 || i==0 && i==5 || i==1 && i==0 || i==1 && i==2 || i==1 && i==3 || i==1 && i==5 || i==2 && i==0 || i==2 && i==1 || i==2 && i==3 || i==2 && i==4 || i==3 && i==1 || i==3 && i==2 || i==3 && i==4 || i==3 && i==5 || i==4 && i==0 || i==4 && i==2 || i==4 && i==3 || i==4 && i==5 || i==5 && i==0 || i==5 && i==1 || i==5 && i==3 || i==5 && i==4 ) {
					artyom.say("Usted podría estar padeciendo de tuberculosis, es necesario tratarla mediante la combinación de dos antibióticos, como el soniacida, rifampicina, pirazinamida, etambutol y estreptomicina, para mayor seguridad acudad con un especialista. Para mayor seguridad acuda a un Nutricionista ");
				}
			}
		},

		{
		// sintomas anemia
		indexes:["tengo cansancio corporal","tengo palidez cutánea","me da taquicardia", "tengo taquicardia", "tengo problemas para respirar", "cansancio corporal", "palidez cutánea", "taquicardia", "problemas para respirar" ],
			action: function(i){
				if (i==0 || i==1 || i==2 || i==3 || i==4 ) {
					artyom.say("Usted podría estar padeciendo de anemia, esto puede ser producto de muchas y diversas enfermedades, es imprescindible realizar más análisis antes de realizar ningún tratamiento, pero debería consumir alimentos o fármacos que contengan hierro, vitamina B12 o ácido fólico. Para mayor información acuda a un Nutricionista");
				}
				if (i==0 && i==1 || i==0 && i==2 || i==0 && i==3 || i==0 && i==4 || i==0 && i==6 || i==0 && i==7 || i==0 && i==8 || i==1 && i==0 || i==1 && i==2 || i==1 && i==3|| i==1 && i==4 || i==1 && i==5 || i==1 && i==7 || i==1 && i==8 || i==2 && i==0 || i==2 && i==1 || i==2 && i==3 || i==2 && i==4 || i==2 && i==5 || i==2 && i==6 || i==2 && i==8 || i==3 && i==0 || i==3 && i==1 || i==3 && i==2 || i==3 && i==4 || i==3 && i==5 || i==3 && i==6 || i==3 && i==8 || i==4 && i==0 || i==4 && i==1 || i==4 && i==2 || i==4 && i==3 || i==4 && i==5 || i==4 && i==6 || i==4 && i==7 || i==4 && i==8 ) {
					artyom.say("Usted podría estar padeciendo de anemia, esto puede ser producto de muchas y diversas enfermedades, es imprescindible realizar más análisis antes de realizar ningún tratamiento, pero debería consumir alimentos o fármacos que contengan hierro, vitamina B12 o ácido fólico. Para mayor información acuda a un Nutricionista");
				}
				if (i==5 && i==6 && i==7 && i==8 || i==6 && i==5 && i==7 && i==8 || i==7 && i==6 && i==5 && i==8 || i==8 && i==6 && i==5 && i==7 || i==8 && i==7 && i==6 && i==5 ) {
					artyom.say("Usted podría estar padeciendo de anemia, esto puede ser producto de muchas y diversas enfermedades, es imprescindible realizar más análisis antes de realizar ningún tratamiento, pero debería consumir alimentos o fármacos que contengan hierro, vitamina B12 o ácido fólico. Para mayor información acuda a un Nutricionista");
				}


			}
		},

{
		// ayuda de tipos de enfermedades a tratar
		indexes:["ayuda","lista de enfermedades"],
			action: function(i){
				if (i==0 || i==1 ) {
					artyom.say("Este sistema es apto para el diagnóstico de las siguientes enfermedades, gripe, rubeola, malaria, hepatitis, tuberculosis y anemia");
				}
			}
		},

		{
			indexes:['limpiar'],
			action: function(){
				$('#textovoz').val('');
			}
		}

	]);

</script>

<!--  -->




</HTML>
