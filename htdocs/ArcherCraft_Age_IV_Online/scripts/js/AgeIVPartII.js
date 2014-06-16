var Player=JSON.parse(localStorage.Player);
Player.gender = prompt('Gender:');
  switch(Player.gender){
    case 'M':
      Player.title='baron';
      break;
      case 'F':
        Player.title='baroness';
        break;
        default:
        throw 'Does not compute ';
        break;
  }
alert('Welcome again, '+Player.name);
alert("first , there's a very special thing happening");

setTimeout(5000,function(){Player.defense=50;});
while(slaying){
    var Action=prompt("attack, or flee?");
     switch(Action){
    case "attack":
var youHit=Math.random();
break;
case "applyarmor":
  var defense=Player.defense;
  alert('armor applied. ' + Player.name + 'ready to take its down  opponent with it! ');
  break;
  case 'remove':
    var defense = null;
    alert(Player.name+ 'has taken off his armor');
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
      }else if(weapon===arbalest){
        var choice_w=parseInt(prompt("Choose an Arbalest:"),10);
        attack=Player.arms.arbalest[choice_w].attack;
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
if(youKill >= 0.5){
alert("Enemy Killed With Ballista");
Enemy_health=0;
slaying=false;
}else if(youKill < 0.5){
alert("You missed!");
Action=prompt("Try Again!");
}
break;
default:   
 Action=prompt("attack, or flee?");
break;
}
    if (youHit>0.50) {      
alert("You hit the"+ Enemy.name +" and did "+ Player.attack + "damage!");     
Enemy_health=Enemy_health-Player.attack;
if(Enemy_health===0){
  alert("You are Victorious!");
  lose=false;
  slaying=false;
  Player.exp+=10;
}
else if(health===0){
             alert("The" +Enemy.name + "kills you! You're toast.");
             lose=true;          
slaying = false; 
break;
}
  else if(youHit<0.50){
      alert("you miss!");
      var enemyHit = Math.floor(Math.random())
      health=health-(Enemy.attack-defense);
      alert(health);  
}}  }
