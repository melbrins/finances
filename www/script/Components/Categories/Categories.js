import React, { Component } from 'react';
import './scss/categories.scss';
import uuid from 'uuid';
import CategoryItem from './CategoryItem';


class Categories extends Component {
    constructor(){
        super();
        this.state = {
            categories: []
        }
    }

    componentWillMount(){
        this.setState({
            categories: [
                {
                    id: uuid.v4(),
                    title: 'House',
                    total: '1,200.00',
                    nbrTransactions: '1',
                    left: '200.00',
                    currency: '£'
                },{
                    id: uuid.v4(),
                    title: 'Bills',
                    total: '316.00',
                    nbrTransactions: '10',
                    left: '100.00',
                    currency: '£'
                },{
                    id: uuid.v4(),
                    title: 'Groceries',
                    total: '1,200',
                    nbrTransactions: '22',
                    left: '100.00',
                    currency: '£'
                },{
                    id: uuid.v4(),
                    title: 'Drinks',
                    total: '63.80',
                    nbrTransactions: '22',
                    left: '100',
                    currency: '£'
                }
            ]
        })
    }

    render(){

        let categories;

        if(this.state.categories){
            categories = this.state.categories.map(category => {
                return(
                    <CategoryItem category={category} key={category.id}/>
                )
            });
        }

        return (
            <section className="categories">
                {categories}
            </section>
        );
    }
}

export default Categories;