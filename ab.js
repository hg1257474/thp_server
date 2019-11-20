const parser = require("parse-torrent");
const fs = require("fs");
console.log(parser(fs.readFileSync(__dirname + "/a.torrent")));
