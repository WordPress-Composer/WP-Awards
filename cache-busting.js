const jsonfile = require('jsonfile');
const file = './dist/manifest.json';

const data = {
    version: Date.now()
}

jsonfile.writeFile(file, data, function(err) {
    if (err) {
        throw new Error(err);
    }
});