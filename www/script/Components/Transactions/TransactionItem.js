import React, { Component } from 'react';

class TransactionItem extends Component{

    render(){
        let transactionName = this.props.data.name.split(" ON ");

        return(

            <li className="transaction">
                {this.props.day}
                <div className="transaction-details">
                    <h3>{transactionName[0]}</h3>
                    <p>{this.props.data.category_name}</p>
                </div>

                <div className="transaction-amounts">
                    <h3>£{this.props.data.amount}</h3>
                    <p>{this.props.data.day}</p>
                </div>
            </li>
        );
    }
}

export default TransactionItem;