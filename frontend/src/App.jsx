import { BrowserRouter as Router, Routes, Route } from "react-router-dom"
import AddExpenses from "./AddExpenses"
import Dashboard from "./Dashboard"
import ManageExpenses from "./ManageExpenses"

function App() {


  return (
    <>
      <Router>
        <Routes>
          <Route path='/' element={<Dashboard/>}/>
          <Route path='/expenses' element={<AddExpenses/>}/>
          <Route path='/manage' element={<ManageExpenses/>}/>   

        </Routes>
      </Router>
      
    </>
  )
}

export default App
