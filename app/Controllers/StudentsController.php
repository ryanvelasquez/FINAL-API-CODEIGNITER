<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StudentsModel;

class StudentsController extends BaseController
{
    public function index()
    {
        $fetchstudent = new StudentsModel();

        $data['students'] = $fetchstudent->findAll();

        return view('students/list', $data); 
    }

    public function createStudent()
    {
        $data['studentNumber'] = '20000_' .uniqid();
        return view('students/add' , $data);
    }

    public function storeStudent()
    {
        $insertStudents = new StudentsModel();

        if($img = $this->request->getFile('studentProfile')){
            if($img->isValid() && ! $img->hasMoved()){
                $imageName = $img->getRandomName();
                $img->move('uploads/', $imageName);
            }
        }

        $data = array(

        'Student_name' => $this->request->getPost('studentName'),
        'Student_id' => $this->request->getPost('studentNum'),
        'Student_section' => $this->request->getPost('studentSection'),
        'Student_course' => $this->request->getPost('studentCourse'),
        'Student_batch' => $this->request->getPost('studentBatch'),
        'Student_year_level' => $this->request->getPost('studentYear'),
        'Student_profile' => $imageName,
        
        );

        $insertStudents ->insert($data);

        return redirect()->to('/students')->with('success', 'Student added successfully!!');
    
    }

    public function editStudent($id)
    {
        $editStudent = new StudentsModel();
        $data['student'] = $editStudent->find($id);
    
        return view('students/edit', $data);
    }

    public function updateStudent($id)
    {
       $updateStudent = new StudentsModel();
       $db = db_connect();

       if($img = $this->request->getFile('studentProfile')){
        if($img->isValid() && ! $img->hasMoved()){
            $imageName = $img->getRandomName();
            $img->move('uploads/', $imageName);
        }
    }

        if(!empty($_FILES['studentProfile']['name'])) {
        $db->query("Update tbl_students SET Student_profile = '$imageName' WHERE id = '$id'");  
        }
        
        $data = array(

            'Student_name' => $this->request->getPost('studentName'),
            'Student_id' => $this->request->getPost('studentNum'),
            'Student_section' => $this->request->getPost('studentSection'),
            'Student_course' => $this->request->getPost('studentCourse'),
            'Student_batch' => $this->request->getPost('studentBatch'),
            'Student_year_level' => $this->request->getPost('studentYear'),
            
            
            );

        $updateStudent ->update($id, $data);

        return redirect()->to('/students')->with('success', 'Student Updated successfully!!');
    }

    public function deleteStudent($id)
    {
        $deleteStudent = new StudentsModel();
        $deleteStudent->delete($id);

        return redirect()->to('/students')->with('success', 'Student Deleted successfully!!');
    }
}