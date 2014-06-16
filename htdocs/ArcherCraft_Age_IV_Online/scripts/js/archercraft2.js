var KILLED = 0.50;

function setCookie(cname,cvalue,exdays)
{
var d = new Date();
d.setTime(d.getTime()+(exdays*24*60*60*1000));
var expires = "expires="+d.toGMTString();
document.cookie = cname + "=" + cvalue + "; " + expires;
}

function getCookie(cname)
{
var name = cname + "=";
var ca = document.cookie.split(';');
for(var i=0; i<ca.length; i++)
  {
  var c = ca[i].trim();
  if (c.indexOf(name)===0) return c.substring(name.length,c.length);
}
return "";
}
try{
var Player=JSON.parse(getCookie('username'));
var Enemy={
name:"Militia",
attack:10,
health:{
max:100,
current:100
}
};

for(var i=200;i<201;i-=1){
var slaying=true;
var Enemy_health=Enemy.health.current;
var health=Player.health.current;
while(slaying===true){
    var Action=prompt("attack, or flee?");
if (Enemy_health===0){
alert("You did it! You slew the" + Enemy.name+ "!");
lose=false;
 slaying = false;
      } else if(Enemy_health >0){
     switch(Action){
    case "attack":
var youHit=Math.random();
break;
case "flee":
     alert("You lose!");
      slaying = false;
      break;
      case "changeweapon":
      var weapon=prompt('crossbow or sword?');
      if(weapon==="sword"){
      attack=Player.attack;
      }else if(weapon==="crossbow"){
      attack=Player.arms.crossbow_w_sword.attack;
      }
      youHit=Math.random();
      break;
case "heal":
Player.inventory.FAK.qty=Player.inventory.FAK.qty-1;
health+=Player.inventory.FAK.heal;
alert(Player.health.current);
Action=prompt("attack, or flee?");
break;
case "ninja_attack":
attack=Player.arms.crossbow_w_sword.attack;
youHit=Math.random();
attack= Player.attack;
youHit=Math.random();
break;
case "ballista":
var youKill=Math.random();
if(youKill >= KILLED){
alert("Enemy Killed With Ballista");
Enemy_health=0;
slaying=false;
}else if(youKill < KILLED){
alert("You missed!");
Action=prompt("Try Again!");
}
break;
default:
 Action=prompt("attack, or flee?");
break;
}}
    if (youHit>0.50) {
alert("You hit the"+ Enemy.name +" and did "+ Player.attack + "damage!");
Enemy_health=Enemy_health-Player.attack;
    }
if(health===0){
             alert(" The" +Enemy.name + "kills you! You're toast.");
             lose=true;
slaying = false;
break;
}
  else if(youHit<0.50){
      alert("you miss!");
      health=health-Enemy.attack;
      alert(health);
}}


  if(i<200){
  switch(i){
case(150):
alert("Your are now at level 5!");

Player.level+=1;
Player.health.current+=100;
Player.health.max+=100;
Player.attack+=10;

break;

case(100):
alert("Your are now at level 6!");

Player.level+=1;
Player.health.current+=100;
Player.health.max+=100;
Player.attack+=10;

break;
case(50):
alert("Your are now at level 7!");

Player.level+=1;
Player.health.current+=100;
Player.health.max+=100;
Player.attack+=10;
break;
case(0):
 alert('You Win!');
    setCookie(Player.name,JSON.stringify(Player),1000);var shield1= Object.create
({defense: 50, image: "_static/images/shield1.png"});  localStorage.shield1=JSON.stringify(shield1);
alert("Your are now declared victorius and a baronet!");

Player.level+=1;
Player.health.current+=100;
Player.health.max+=100;
Player.attack+=10;

window.location.assign('AgeIII.htm');
break;
default:
Player.health+=100;
break;
}
}else if(i===0){
    alert('You Win!');
break;
}else if(i>0 && health===0){
alert("You Lose!");
window.location.assign("index.htm");
}
Player.health.max=400;
Player.health.current=400;
Player.title="knight";
Player.attack=40;
alert("roma: attacks are made in rome. mission : stop terrorists from sieging Rome!");  Enemy_health=100;
}
alert("You Lose!");
window.location.assign("index.htm");
}catch(error){
alert("Error%s Detected!",error);
}finally{
alert('Fixed');
}
