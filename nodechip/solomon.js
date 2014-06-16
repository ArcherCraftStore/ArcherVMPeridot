var httpchip = require("http");

httpchip.createServer(function(request, response)  {
    response.writeHead(200, {'Content-Type:': 'text/html'}); 
     fs.readFile('index.html', function(err,contents){
      response.write(contents);
      response.end("App is running on port 2375");
  });
    
}).listen(2375);
