const express = require('express'),
    index = require('../../index'),
    bdd = require('../modele/bdd'),
    bcrypt = require('bcryptjs')

exports.login = (req, res) => {
    const data = req.body;
    if (index.exist(data.email) == false || index.exist(data.password) == false) {
        index.sendReturn(res, 403, { error: true, message: "L'email/password est manquant" })
    }
    if (index.emailFormat(data.email) == false) {
        index.sendReturn(res, 409, { error: true, message: "L'email n'est écrit dans un format conforme" })
    } else {
        bdd.query(" SELECT * FROM user WHERE email = '" + data.email + "'", (error, results) => {
            if (error != null) {
                index.sendReturn(res, 401, { error: true, message: "Requête impossible" })
            } else if (results.length == 0) {
                index.sendReturn(res, 401, { error: true, message: "Votre Email/Password est erroné" });
            } else {
                bcrypt.compare(data.password, results[0].password).then(isOK => {
                    if (isOK) {
                        index.getUsers(res, ' WHERE id = ' + idUser, 200, "L'utilisateur a été authentifié succès");
                    } else {
                        index.sendReturn(res, 401, { error: true, message: "Votre Email/Password est erronée" });
                    }
                });
            }
        });
    }
}

exports.register = async(req, res) => {
    const data = req.body
        // Vérification de si les données sont bien présentes dans le body
    let error = false
    if (index.exist(data.firstname) == false)
        error = true
    if (index.exist(data.lastname) == false)
        error = true
    if (index.exist(data.email) == false)
        error = true
    if (index.exist(data.password) == false)
        error = true
    if (index.exist(data.avatar) == false)
        error = true
    if (error == true)
        index.sendReturn(res, 403, { error: true, message: "L'une ou plusieurs données obligatoire sont manquantes" })
    else {
        // Vérification du format de la date, de l'email et du sexe
        if (index.emailFormat(data.email) == false)
            index.sendReturn(res, 409, { error: true, message: "L'une des données obligatoire ne sont pas conformes" })
        else {
            // Vérification de si l'email existe déjà
            if (await index.emailExist(data.email))
                index.sendReturn(res, 422, { error: true, message: "Votre email n'est pas correct" })
                //^^Message d'erreur requête verif email
            else {

                // Encryptage du mot de passe
                data.password = await new Promise(resolve => {
                    bcrypt.genSalt(10, async(err, salt) => {
                        return await bcrypt.hash(data.password, salt, (err, hash) => {
                            resolve(hash)
                        });
                    });
                })

                // Insertion de l'utilisateur en base de données
                toInsert = {
                    firstname: data.firstname.trim(),
                    lastname: data.lastname.trim(),
                    password: data.password,
                    avatar: data.avatar,
                    email: data.email.trim().toLowerCase(),
                };

                bdd.query("INSERT INTO user SET ?", toInsert, (error, results) => {
                    if (error) {
                        console.log(error)
                        index.sendReturn(res, 401, { error: true, message: "La requête d'inscription en base de donnée n'a pas fonctionné" })
                    } else
                        index.getUsers(res, " WHERE id = " + results.insertId, 201, "L' utilisateur a bien été crée avec succès")
                });
            }
        }
    }
}