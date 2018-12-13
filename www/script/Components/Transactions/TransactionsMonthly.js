import React, { Component } from 'react';
import TransactionItem from "./TransactionItem";
import uuid from "uuid";

let day;

class TransactionsMonthly extends Component{
    constructor(){
        super();

        this.state = {
            amount: []
        }
    }

    componentWillMount(){
        if(this.props.data.transactionDay) {
            let amountPerDay = [];

            Object.values(this.props.data.transactionDay).map( transaction => {

                amountPerDay.push({
                    int: transaction.amount,
                    day: transaction.day
                });

                // console.log(transaction.day);
                this.setState({
                    amount: [{
                            int: transaction.amount
                        }]
                });

                // console.log(amountPerDay);

            });
        }
    }

    dayComparaison(transaction){
        let newDay = <div className="new-day">{transaction.day}</div>;

        if(day !== transaction.day) {
            day = transaction.day;
            return newDay;

        }else{
            return '';
        }
    }

    render(){

        let monthsJSON;
        let daySpent;

        if(this.props.data.transactionDay){

            monthsJSON = Object.values(this.props.data.transactionDay).map( transaction => {

                // console.log(this.dayComparaison(transaction));
                return (
                    <TransactionItem data={transaction}  day={this.dayComparaison(transaction)} key={transaction.id}/>
                )
            });

        }
        return monthsJSON;
    }
}

export default TransactionsMonthly;