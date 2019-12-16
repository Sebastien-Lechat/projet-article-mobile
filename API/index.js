const express = require('express'),
    path = require('path'),
    bodyParser = require('body-parser'),
    userCtrl = require('./src/route/user'),
    articleCtrl = require('./src/route/article'),
    fluxCtrl = require('./src/route/flux'),
    app = express(),
    bdd = require('./src/modele/bdd'),
    port = 3000,
    cors = require('cors')

app.use(cors())

// parse application/x-www-form-urlencoded
app.use(bodyParser.urlencoded({ extended: false }))

app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname + '/index.html'))
})

app.post('/user', userCtrl.login)

app.post('/user', userCtrl.register)

app.get('/article', articleCtrl.getAllArticle)

app.get('/flux', fluxCtrl.getAllFlux)

app.get('/article/:id', articleCtrl.getArticleById)

app.get('/flux/:id', fluxCtrl.getAllFlux)

// app.post('/article', articleCtrl.addArticle)

// app.post('/flux', fluxCtrl.addFlux)

// app.put('/article/:id', articleCtrl.updateArticle)

// app.put('/flux/:id', fluxCtrl.updateFlux)

// app.delete('/article/:id', articleCtrl.deleteArticle)

// app.delete('/flux/:id', fluxCtrl.deleteFlux)

/**
 * Function qui fait un retour d'une donnée
 * @param {Response} res 
 * @param {Number} status 
 * @param {Object} data 
 */
exports.sendReturn = (res, status = 500, data = { error: true, message: "Processing error" }) => {
    res.setHeader('Content-Type', 'application/json')
    try {
        res.status(status).json(data)
    } catch (error) {
        let sendError = { error: true, message: "Processing error" }
        res.status(500).json(sendError)
    }
}

const sendReturn = (res, status = 500, data = { error: true, message: "Processing error" }) => {
    res.setHeader('Content-Type', 'application/json')
    try {
        res.status(status).json(data)
    } catch (error) {
        let sendError = { error: true, message: "Processing error" }
        res.status(500).json(sendError)
    }
}

// Function qui vérifie l'existence d'une data
exports.exist = (data) => {
    if (data == undefined || data.trim().length == 0)
        return false
    else
        return true

}

// Function vérification de si l'email est dans le bon format
exports.emailFormat = (data) => {
    let regexEmail = /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i
    if (data.match(regexEmail) == null)
        return false
    else
        return true
}

// Function vérification de si l'email existe en base de données
exports.emailExist = async(data) => {
    let toReturn = false
    toReturn = await new Promise(resolve => {
        bdd.query("SELECT * FROM `user` WHERE `email` LIKE '" + data.trim().toLowerCase() + "'", (error, results) => {
            resolve((results.length > 0) ? true : false)
        })
    })
    return toReturn
}

// Function qui vérifie si l'id existe et si il est conforme
exports.verifId = (data, res) => {
    if (data === undefined)
        this.sendReturn(res, 403, { error: true, message: "Veuillez insérer un id" })
    else if (data.match(/^[0-9]*$/gm) == null)
        this.sendReturn(res, 400, { error: true, message: "L'id envoyé n'est pas conforme" })
}

// Function qui récupère tous les utilisateurs dans la table users
exports.getUsers = (res, where = "", port = 200, messageSend = "") => {
    bdd.query("SELECT * FROM user" + where, (error, results, fields) => {
        // Si erreur dans la requête
        if (error) {
            console.log(error)
            sendReturn(res, port, { error: false, message: "Erreur dans la requête" });
        }
        // Si le resultat n'existe pas
        else if (results === undefined)
            sendReturn(res, port, { error: false, message: "Aucun résultat pour la requête" });
        // Si la liste des utilises est vide
        else if (results.length == 0)
            sendReturn(res, 409, { error: true, message: "L'id envoyez n'existe pas" })
        else {
            sendReturn(res, port, {
                error: false,
                user: results[0]
            })
        }
    });
}

app.listen(port, () => console.log(`Example app listening on port port!`))