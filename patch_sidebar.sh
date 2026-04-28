#!/bin/bash
sed -i '/protected static \?string \$navigationLabel/a \    protected static ?string $navigationGroup = "Data Master";' app/Filament/Resources/Schools/SchoolResource.php
sed -i '/protected static \?string \$navigationLabel/a \    protected static ?string $navigationGroup = "Data Master";' app/Filament/Resources/Classrooms/ClassroomResource.php
sed -i '/protected static \?string \$navigationLabel/a \    protected static ?string $navigationGroup = "Manajemen Pengguna";' app/Filament/Resources/Users/UserResource.php
sed -i '/protected static \?string \$navigationLabel/a \    protected static ?string $navigationGroup = "Manajemen Ujian";' app/Filament/Resources/Questions/QuestionResource.php
sed -i '/protected static \?string \$navigationLabel/a \    protected static ?string $navigationGroup = "Manajemen Ujian";' app/Filament/Resources/Exams/ExamResource.php
sed -i '/protected static \?string \$navigationLabel/a \    protected static ?string $navigationGroup = "Manajemen Ujian";' app/Filament/Resources/ExamResults/ExamResultResource.php
sed -i '/protected static \?string \$navigationLabel/a \    public static ?string $pluralModelLabel = "Data Sekolah";' app/Filament/Resources/Schools/SchoolResource.php
