const express = require("express")
const cors = require('cors');
const port = 3000;

const app = express()

app.use(cors())

const data = require('./data.json')

// "Afghanistan": {
//     "population": 38928341,
//         "totals": {
//         "deaths": 253719,
//             "recovered": 5166205,
//                 "confirmed": 7435755
//     },
//     "avg": {
//         "deaths": 0.006517590872932397,
//             "recovered": 0.13271063876058833,
//                 "confirmed": 0.19101135083049134
//     },
//     "days": [
//     {
//         "date": "2020-01-23",
//         "timestamp": 1579734000,
//         "incremental": {
//             "deaths": 0,
//             "recovered": 0,
//             "confirmed": 0,
//             "avg": {
//                 "deaths": 0,
//                 "recovered": 0,
//                 "confirmed": 0
//             }
//         },
//         "diff": {
//             "deaths": 0,
//             "recovered": 0,
//             "confirmed": 0,
//             "avg": {
//                 "deaths": 0,
//                 "recovered": 0,
//                 "confirmed": 0
//             }
//         }
//     },
// ]
// }

const parseData = async (period) => {
    let returnData = {}
    period += 1;
    for (let i in data) {
        console.log("====", i)
        returnData[i] = {}
        returnData[i].population = data[i].population
        returnData[i].periods = []

        let lastPeriod;
        let j = 0;
        for (let x = data[i].days.length - (5 * period); x < data[i].days.length; x += period) {
            if(j !== 0){

                let currentPeriod = data[i].days[x]
                let tmp = data[i].days[x]
                
                tmp.incremental.confirmed = currentPeriod.incremental.confirmed - lastPeriod.incremental.confirmed
                tmp.incremental.recovered = currentPeriod.incremental.recovered - lastPeriod.incremental.recovered
                tmp.incremental.deaths = currentPeriod.incremental.deaths - lastPeriod.incremental.deaths

                tmp.diff.confirmed = currentPeriod.diff.confirmed - lastPeriod.diff.confirmed
                tmp.diff.recovered = currentPeriod.diff.recovered - lastPeriod.diff.recovered
                tmp.diff.deaths = currentPeriod.diff.deaths - lastPeriod.diff.deaths

                console.log(tmp)

                returnData[i].periods.push(tmp)

            } else {
                lastPeriod = data[i].days[x];
            }

            j++;
        }
        /*for (let x = data[i].days.length - (5 * period); x < data[i].days.length; x += period) {
            lastPeriod = data[i].days[x].incremental.confirmed - data[i].days[x - period].incremental.confirmed
            // console.log(lastPeriod)
            // console.log(x)
            returnData[i].lastDays.push(data[i].days[x].incremental.confirmed - data[i].days[x - period].incremental.confirmed)
        }
        
        returnData[i].points = []
        for (let j = 0; j < data[i].days.length; j++) {
            returnData[i].points.push({ x: j, y: data[i].days[j].incremental.confirmed })
        }
        //*/


    }

    return returnData
}

app.get('/', async (req, res) => {
    // np. ?period=7
    const returnData = await parseData(+req.query.period)
    res.status(200).json(returnData)
})

app.listen(port, () => console.log("Server runnin"))
