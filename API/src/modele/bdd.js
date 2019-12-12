mysql = require('mysql'),
    bdd = mysql.createConnection({
        multipleStatements: true,
        host: 'localhost',
        user: 'root',
        password: '',
        database: 'projet-article-mobile'
    })

bdd.connect((err) => {
    if (err) {
        console.error('error connecting: ' + err.stack);
        return;
    }
    console.log('connected');
})

module.exports = bdd;