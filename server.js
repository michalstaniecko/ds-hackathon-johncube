const express = require("express")
const cors = require('cors');
const port = 3000;

const app = express()

app.use(cors())

const data = require('./data.json')

const parseData = async (period) => {
    let returnData = {}
    //period += 1;
    for (let i in data) {
        console.log("====", i)
        returnData[i] = {}
        returnData[i].population = data[i].population
        returnData[i].periods = []

        let lastPeriod;
        let j = 0;
        for (let x = data[i].days.length - (6 * period); x < data[i].days.length; x += period) {
            if(j !== 0){

                let currentPeriod = data[i].days[x]
                let tmp = {
                    date: currentPeriod.date,
                    diff: {},
                    incremental: {}
                };
                
                tmp.incremental.confirmed = currentPeriod.incremental.confirmed;
                tmp.incremental.recovered = currentPeriod.incremental.recovered;
                tmp.incremental.deaths = currentPeriod.incremental.deaths;

                tmp.realDiff = {
                    confirmed: currentPeriod.diff.confirmed,
                    recovered: currentPeriod.diff.recovered,
                    deaths: currentPeriod.diff.deaths,
                }

                tmp.diff = {
                    confirmed: currentPeriod.diff.confirmed - lastPeriod.diff.confirmed,
                    recovered: currentPeriod.diff.recovered - lastPeriod.diff.recovered,
                    deaths: currentPeriod.diff.deaths - lastPeriod.diff.deaths,
                }

                tmp.diffAvg = {
                    confirmed: tmp.diff.confirmed / returnData[i].population,
                    recovered: tmp.diff.recovered / returnData[i].population,
                    deaths: tmp.diff.deaths / returnData[i].population,
                }

                returnData[i].periods.push(tmp)
                lastPeriod = data[i].days[x];
            } else {
                lastPeriod = data[i].days[x];
            }

            j++;
        }
    }

    return returnData
}

app.get('/', async (req, res) => {
    // np. ?period=7
    const returnData = await parseData(+req.query.period)
    res.status(200).json(returnData)
})

app.listen(port, () => console.log("Server runnin"))
