function Roll(){
var die1 = Math.floor(Math.random()*6 + 1);
var die2 = Math.floor(Math.random()*6 + 1);
var score;

// This time if either die roll is 1 then score should be 0 
if(die1===0 || die2===0){
score=0;
}else {
  // here we need to check if there are doubles.  If so, score should be
  // double the sum of the two dice
  if(die1==die2){
     score=2*(die1+die2);
  }
  
  else{
      score=die1+die2;
  }
}

alert("You rolled a "+die1+" and a "+die2+" for a score of "+score);
}