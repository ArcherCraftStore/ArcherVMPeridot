<!DOCTYPE html>

<html>

    <head>

        <title>ArcherCraft Console</title>

        <meta content='width=device-width, initial-scale=1.0, user-scalable=no' name='viewport'>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
</script>
 
       <script>
$(document).ready(function(){

        $('#p1 h1').after('<p>Welcome to ACO Age 6!</p>');

  $('.pull-me').click(function(){
 
    $('.panel').slideToggle('slow');
 
 });
 
 
});
</script>

<script>

$(document).ready(function(){

$('#button').click(function() {
 
   var toAdd = $('input[name=checkListItem]').val();

    $('.list').append('<div class="item">' + toAdd + '</div>')
});

$(document).on('click', '.item', function() {

   $(this).remove();
});
});
</script>


<script>
$(document).ready(function() {
    
$('button').click(function() {
    	
var toAdd = $("input[name=message]").val();
        $('#messages').append("<p>"+toAdd+"</p>");

    });

});
</script>
<script>
$(document).ready(function() {
    $('div#Krypton').click(function() {
        $(this).effect('explode');
    });
});
</script>
<style>
body {
    
margin:0 auto;
   
padding:0;
	
width:200px;
    
text-align:center;

}

.pull-me{

    -webkit-box-shadow: 0 0 8px #FFD700;
 
   -moz-box-shadow: 0 0 8px #FFD700;
 
   box-shadow: 0 0 8px #FFD700;

    cursor:pointer;


}

.panel {
	background: #ffffbd;

    background-size:90% 90%;

    height:300px;

	display:none;

    font-family:garamond,times-new-roman,serif;

}

.panel *{
  
  text-align:center;

}
.slide {
	
margin:0;
	
padding:0;
	
border-top:solid 2px #cc0000;

}

.pull-me {
	display:block;
    
position:relative;
    
right:-25px;
    
width:150px;
    
height:20px;
	font-family:arial,sans-serif;
    
font-size:14px;
	
color:#ffffff;
    background:#cc0000;
	
text-decoration:none;
    
-moz-border-bottom-left-radius:5px;
    -moz-border-bottom-right-radius:5px;
    
border-bottom-left-radius:5px;
    
border-bottom-right-radius:5px;

}

.pull-me * {
    
text-align:center;

}

h2 {
    
font-family:arial;

}

form#list{
    display: inline-block;

}
#button{
    
display: inline-block;
    
height:20px;
	
width:70px;
	background-color:#cc0000;

font-family:arial;
	
font-weight:bold;
	color:#ffffff;
	
border-radius: 5px;
	
text-align:center;
	
margin-top:2px;

}


.list {
	font-family:garamond;
	
color:#cc0000;

}
form#msges {
 
font-size: 12px;
    
font-family: Verdana, Arial, Sans-Serif;
    
display: inline-block;

}

#messages {
    
font-size: 14px;
	
font-family: Segoe UI, Garamond, Times, Serif;
color: white;
}

body {
    
background-image: url('http://bit.ly/UpQgJ6');
    
repeat: no-repeat;

}

}
.red {
    
background-color: #CC0000;
    
background-image: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#330000), to(#CC0000));
    background-image: -webkit-linear-gradient(left, #330000, #CC0000);
    
background-image:    -moz-linear-gradient(left, #330000, #CC0000);
    
background-image:     -ms-linear-gradient(left, #330000, #CC0000);
    
background-image:      -o-linear-gradient(left, #330000, #CC0000);

}

div#Krypton {
    height: 100px;
    width: 100px;
    border-radius: 100%;
    background-color: #008800;
    background-image: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#003500), to(#008800));
    background-image: -webkit-linear-gradient(left, #003500, #008800);
    background-image:    -moz-linear-gradient(left, #003500, #008800);
    background-image:     -ms-linear-gradient(left, #003500, #008800);
    background-image:      -o-linear-gradient(left, #003500, #008800);
}
</style>
    
</head>
    
<body>
        
<div class="panel" id="p1">

        <br />
       
 <br />
     
<h1>Panel</h1>
     	
<h2>To Do</h2>
		
<form name="checkListForm" id="list">

			<input type="text" name="checkListItem"/>
		
</form>
		
<div id="button">Add!</div>
		<br/>
		
<div class="list"></div>
        
</div>
        
<p class="slide"><div class="pull-me">Slide Up/Down</div>
</p>
 
<form id="msges">
        MESSAGE:
<input type="text" name="message" value="Type your text here!">
    
    </form>
        
<button>Add!</button>
<br/>
        
<div id="messages">
</div>
   <div id="Krypton"></div>

</body>

</html>