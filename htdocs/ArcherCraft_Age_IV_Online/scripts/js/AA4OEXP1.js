function Arbalest(name,ammo,attack){
	this.attack=attack;
	this.name=name;
	this.ammo=ammo;
	this.getFiringPower=function(){
		return this.attack;
	};
}
var Player_modified=new Object();
Player_modified.name=prompt("Name?:"),
Player_modified.title="squire";
Player_modified.health=new Object();
Player_modified.health.max=1200;
Player_modified.inventory=new Object();
Player_modified.inventory.FAK=new Object();
Player_modified.inventory.FAK.heal= new Object();
Player_modified.inventory.FAK.qty=20;
Player_modified.level=1;
Player_modified.attack=110;
Player_modified.arms={
crossbow_w_sword:{
attack:20
}
};
Player_modified.class="Nobility";
Player_modified.level+=1;
Player_modified.health.max+=100;
Player_modified.health.current=Player_modified.health.max;
Player_modified.attack+=10;
Player_modified.exp+=100;
Player_modified.title="baron";
Player_modified.arms.arbalest= new Array();
for(var i=1; i<6; i++){
Player_modified.arms.arbalest[i]=new Arbalest(i,i*10,i*20);
}
if(localStorage.Player !== null){ 
  var Player = JSON.parse(localStorage.Player);
}else{
  var Player = Player_modified;
}
alert('The mongols were among the numerous nomadic tribes who lived in Central  Asia.');
Player.longbow={ 
  ammo:200,
  attack: 100,
};
alert('From 1200 to 1206, a clan leader named Genghis Khan unified the Mongols');
alert('Under Genghis Khan,  & later khans, the Mongols conquered Central Asia,China, Korea, Russia, and The Islamic Empire');
alert("Your Town is in Serious Danger: Genghis Khan's Army is charging at your town. defend it!");