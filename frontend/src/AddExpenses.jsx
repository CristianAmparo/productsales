import React, { useState } from 'react';
import axios from 'axios';

function AddExpenses() {
  const [success, setSuccess] = useState('');
  const [error, setError] = useState('');
  const [formData, setFormData] = useState({
    name: '',
    category: '',
    price: '',
    quantity: '',
  });

  const { name, category, price, quantity } = formData;

  const handleChange = (e) => {
    setFormData((prev) => ({
      ...prev,
      [e.target.name]: e.target.value,
    }));
  };

  const handleSubmit = (e) => {
  e.preventDefault();
  axios
    .post('http://localhost/productsales/itec116-amparo/expenses', formData)
    .then((response) => {
      console.log(response.data);
      setSuccess('Register Successfully');
      setError('');
    })
    .catch((error) => {
      if (error.response) {
        // The request was made, but the server responded with an error.
        console.log(error.response.data);
        setError(error.response.data);
      } else if (error.request) {
        // The request was made, but no response was received.
        console.log(error.request);
        setError('No response received from the server.');
      } else {
        // Something happened in setting up the request that triggered an error.
        console.log('Error:', error.message);
        setError('An error occurred while sending the request.');
      }
    });
};


  return (
    <>
      <h1>Register</h1>
      <div>
        <form onSubmit={handleSubmit}>
          <label htmlFor="name">Name:</label>
          <input
            type="text"
            id="name"
            name="name"
            value={name}
            placeholder="Enter product name"
            onChange={handleChange}
          />
          <label htmlFor="category">category:</label>
          <input
            type="text"
            id="category"
            name="category"
            value={category}
            placeholder="Enter product category"
            onChange={handleChange}
          />
          <label htmlFor="price">price:</label>
          <input
            type="number"
            id="price"
            name="price"
            value={price}
            placeholder="Enter product price"
            onChange={handleChange}
          />
          <label htmlFor="quantity">quantity:</label>
          <input
            type="number"
            id="quantity"
            name="quantity"
            value={quantity}
            placeholder="Enter product quantity"
            onChange={handleChange}
          />
          <button>Register</button>
        </form>
        {success !== '' && <h1>{success}</h1>}
        {error !== '' && <h1>{error}</h1>}
      </div>
    </>
  );
}

export default AddExpenses;
