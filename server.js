const express = require("express")
const port = 3000;
const fs = require('fs')

const app = express()

const data = require('./data.json')

const parseData = async () => {
    let returnData = {}
    console.log(returnData)
    for (let i in data) {
        // console.log(data[i].population)
        returnData[i] = {}
        returnData[i].name = i
        returnData[i].population = data[i].population
        returnData[i].lastDays = []
        for (let x = data[i].days.length - 5; x < data[i].days.length; x++) {
            returnData[i].lastDays.push(data[i].days[x].incremental.confirmed - data[i].days[x - 1].incremental.confirmed)
        }
        returnData[i].points = []
        for (let j = 0; j < data[i].days.length; j++) {
            returnData[i].points.push({ x: j, y: data[i].days[j].incremental.confirmed })
        }
    }
    // for(let x in returnData) console.log(returnData[x])
    // console.log(returnData)

    return returnData
}

app.get('/', async (req, res) => {
    // console.log(data)
    const returnData = await parseData()
    res.status(200).json(returnData)
})

app.listen(port, () => console.log("Server runnin"))