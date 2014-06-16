var fschip = require("fs");
fschip.readFile('index.html', function(err, contents){
 console.log(contents);
});