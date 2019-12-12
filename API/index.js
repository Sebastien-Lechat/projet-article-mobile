const express = require('express'),
    bodyParser = require('body-parser'),
    app = express(),
    bdd = require('./src/modele/index'),
    port = 3000

// parse application/x-www-form-urlencoded
app.use(bodyParser.urlencoded({ extended: false }))



app.listen(port, () => console.log(`Example app listening on port port!`))