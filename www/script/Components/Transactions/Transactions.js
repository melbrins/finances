import React, { Component } from 'react';
import './scss/transactions.scss';
import uuid from 'uuid';
import TransactionsMonthly from "./TransactionsMonthly";

class Transactions extends Component{
    constructor(){
        super();
        this.state = {
            transactions: []
        };
    }

    componentWillMount(){
        this.setState({
            transactions: window.transactions
        });

    }

    render(){

        let finalJSON;

        if(this.state.transactions) {

            var transactions;
            transactions = this.state.transactions;

            // transactions = transactions.filter(transaction => {
            //     return transaction.day.match('2018-11-14');
            // });

            transactions = transactions.groupBy(transactions, function(d){ d.day});

            console.log(transactions);

            finalJSON = this.state.transactions.map(transaction => {
                // finalJSON = transaction.year.map(year => {
                    return(
                         <p>tst</p>
                    )
                // })

            //         finalJSON = transaction.months.map( month => {
            //
            //             return(
            //                 <li className="month" key={month.id}>
            //                     <h3>{month.name}</h3>
            //
            //                     <ul>
            //                         <TransactionsMonthly data={month} />
            //                     </ul>
            //
            //                 </li>
            //             )
            //
            //         });
            //
            });
        }

        return(
            <ul className="transactions">
                {finalJSON}
            </ul>
        );
    }
}

export default Transactions