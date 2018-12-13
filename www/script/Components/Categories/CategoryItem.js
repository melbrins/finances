import React, { Component } from 'react';
import './scss/categories.scss';

class CategoryItem extends Component {

    render(){
        let categoryIcon;

        if(this.props.category.icon) {
            categoryIcon = <div className="category-logo"></div>;
        }

        return (
            <div className="category">

                {categoryIcon}

                <div className="category-details">
                    <h3>{this.props.category.title}</h3>
                    <p>{this.props.category.nbrTransactions} Transaction(s)</p>
                </div>

                <div className="category-amounts">
                    <h3>{this.props.category.currency}{this.props.category.total}</h3>
                    <p>{this.props.category.currency}{this.props.category.left} left</p>
                </div>
            </div>
        );
    }
}

export default CategoryItem;