const express = require('express');
const mysql = require('mysql2');
const cors = require('cors');

const app = express();
app.use(cors());
app.use(express.json());

// Подключение к MySQL через XAMPP
const db = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '',       // у тебя в XAMPP root без пароля
    database: 'realestate'
});

db.connect(err => {
    if(err) throw err;
    console.log("Connected to MySQL!");
});

// API для статистики (счётчики)
app.get('/api/stats', (req,res) => {
    db.query(`
        SELECT SUM(type='sale') as sale,
               SUM(type='rent') as rent,
               SUM(type='buy_request') as buy_request
        FROM properties
    `, (err,result) => {
        if(err) return res.status(500).send(err);
        res.json(result[0]);
    });
});

// API для добавления заявки
app.post('/api/add', (req,res) => {
    const { name, email, phone, title, price, type } = req.body;

    // Валидация email и телефона
    if(!email.endsWith("@gmail.ru")){
        return res.status(400).json({message:"Email должен быть @gmail.ru"});
    }
    if(!/^[0-9]+$/.test(phone)){
        return res.status(400).json({message:"Телефон только цифры"});
    }

    db.query(
        "INSERT INTO properties (name,email,phone,title,price,type) VALUES (?,?,?,?,?,?)",
        [name,email,phone,title,price,type],
        (err) => {
            if(err) return res.status(500).send(err);
            res.json({message:"Заявка добавлена"});
        }
    );
});

// API для вывода всех заявок
app.get('/api/all', (req,res) => {
    db.query("SELECT * FROM properties ORDER BY created_at DESC", (err,result)=>{
        if(err) return res.status(500).send(err);
        res.json(result);
    });
});

app.listen(3000, () => {
    console.log("Server started on port 3000");
});