SELECT courses.Student_ID, names.Student_NAME, courses.Course_ID, courses.Test_1, courses.Test_2, courses.Test_3, courses.Final_Exam 
FROM courses 
JOIN names ON courses.Student_ID = names.Student_ID 
WHERE courses.Student_ID = :studentNumber AND courses.Course_ID = :courseCode