import React, { useState, useEffect } from 'react'
import axios from 'axios';

function ManageExpenses() {
    const [data, setData] = useState([]);

    useEffect(() => {
        axios.get('http://localhost/productsales/itec116-amparo/expenses')
        .then((response) => {
            setData(response.data);
        })
        .catch((error) => {
            console.error('Error fetching data:', error);
        })
    }, []);

    const onEdit = (id)=> {
        
    }
    const onDelete = (id)=> {
         axios.delete(`http://localhost/productsales/itec116-amparo/expenses/${id}`)
      .then((response) => {
        console.log(response);
      })
      .catch((error) => {
        console.error('Error updating resource:', error);
      });
    }

  return (
    <>
    <h1 className="text-3xl font-bold underline bg-">
      Expenses Table
    </h1>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Price</th>
          <th>Edit</th>
          <th>Delete</th>
        </tr>
      </thead>
      <tbody>
        {data.map((data) => (
          <tr key={data.id}>
            <td>{data.id}</td>
            <td>{data.name}</td>
            <td>{data.category}</td>
            <td>{data.price}</td>
            <td>{data.quantity}</td>
            <td>
              <button onClick={() => onEdit(data.id)}>Edit</button>
            </td>
            <td>
              <button onClick={() => onDelete(data.id)}>Delete</button>
            </td>
          </tr>
        ))}
      </tbody>
    </table>
    </>
  )
}

export default ManageExpenses;