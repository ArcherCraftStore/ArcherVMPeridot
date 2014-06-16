var KILLED = 0.50;

function DB(){
    var client = new Dropbox.Client({key:'4c4ba8kbf84hend'});

// Try to finish OAuth authorization.
console.log('initializing client...');
client.authenticate({interactive: true}, function (error) {
    if (error) {
        alert('Authentication error: ' + error);
    }
});

if (client.isAuthenticated()) {
  console.log('creating datastore...');
var datastoreManager = client.getDatastoreManager();
datastoreManager.openDefaultDatastore(function (error, datastore) {
    if (error) {
        alert('Error opening default datastore: ' + error);
    }
var badgeTable = datastore.getTable('badges');
    // Now you have a datastore. The next few examples can be included here.
});
}
}

