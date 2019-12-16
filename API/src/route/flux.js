const express = require('express'),
    index = require('../../index'),
    bdd = require('../modele/bdd')

exports.getAllFlux = (req, res) => {
    bdd.query(" SELECT * FROM flux", (error, results) => {
        if (error != null) {
            index.sendReturn(res, 401, { error: true, message: "Requête impossible" })
        } else if (results.length == 0) {
            index.sendReturn(res, 401, { error: true, message: "La base de donnée est vide" });
        } else {
            index.sendReturn(res, 200, { error: false, flux: results })
        }
    })
}

exports.getFluxById = (req, res) => {
    index.verifId(req.params.id, res)
    const data = req.body;
    bdd.query(" SELECT * FROM flux WHERE id='" + req.params.id + "'", (error, results) => {
        if (error != null) {
            index.sendReturn(res, 401, { error: true, message: "Requête impossible" })
        } else if (results.length == 0) {
            index.sendReturn(res, 401, { error: true, message: "L'id envoyé n'existe pas" });
        } else {
            index.sendReturn(res, 200, { error: false, flux: results })
        }
    })
}

exports.addFlux = (req, res) => {}

exports.updateFlux = (req, res) => {}

exports.deleteFlux = (req, res) => {}