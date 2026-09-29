<?php

namespace Database\Seeders;

use App\MiniApps\NnvnApisGo\Models\QuizQuestion;
use Illuminate\Database\Seeder;

class NnvnQuizQuestionSeeder extends Seeder
{
    public function run(): void
    {
        // Xóa hết câu hỏi cũ
        QuizQuestion::query()->delete();

        $questions = [
            [
                'question_vi' => 'Novo Nordisk được thành lập vào năm nào?',
                'question_en' => 'What year was Novo Nordisk founded?',
                'options' => ['1923', '1945', '1980'],
                'options_en' => ['1923', '1945', '1980'],
                'correct_index' => 0,
            ],
            [
                'question_vi' => 'Trụ sở chính Novo Nordisk ở quốc gia nào?',
                'question_en' => 'In which country is Novo Nordisk headquartered?',
                'options' => ['Đan Mạch', 'Thụy Điển', 'Na Uy'],
                'options_en' => ['Denmark', 'Sweden', 'Norway'],
                'correct_index' => 0,
            ],
            [
                'question_vi' => 'NNVN là viết tắt của?',
                'question_en' => 'What does NNVN stand for?',
                'options' => ['Novo Nordisk Việt Nam', 'National Network VN', 'New Nord VN'],
                'options_en' => ['Novo Nordisk Vietnam', 'National Network VN', 'New Nord VN'],
                'correct_index' => 0,
            ],
            [
                'question_vi' => 'Novo Nordisk chuyên về lĩnh vực nào?',
                'question_en' => 'What field does Novo Nordisk specialize in?',
                'options' => ['Công nghệ thông tin', 'Dược phẩm & y tế', 'Năng lượng'],
                'options_en' => ['Information technology', 'Pharmaceuticals & healthcare', 'Energy'],
                'correct_index' => 1,
            ],
            [
                'question_vi' => 'Digital Week nhằm mục đích gì?',
                'question_en' => 'What is the purpose of Digital Week?',
                'options' => ['Nghỉ phép', 'Chuyển đổi số & đổi mới', 'Tuyển dụng'],
                'options_en' => ['Vacation', 'Digital transformation & innovation', 'Recruitment'],
                'correct_index' => 1,
            ],
            [
                'question_vi' => 'Logo Novo Nordisk có hình gì?',
                'question_en' => 'What is featured in the Novo Nordisk logo?',
                'options' => ['Con bò Apis', 'Ngôi sao', 'Trái tim'],
                'options_en' => ['The Apis bull', 'A star', 'A heart'],
                'correct_index' => 0,
            ],
            [
                'question_vi' => 'Phòng Collab Zone dùng để làm gì?',
                'question_en' => 'What is the Collab Zone used for?',
                'options' => ['Họp chính thức', 'Brainstorm & sáng tạo', 'Lưu trữ hồ sơ'],
                'options_en' => ['Formal meetings', 'Brainstorming & creativity', 'File storage'],
                'correct_index' => 1,
            ],
            [
                'question_vi' => 'Có bao nhiêu phòng trong văn phòng NNVN?',
                'question_en' => 'How many rooms are in the NNVN office?',
                'options' => ['3 phòng', '5 phòng', '8 phòng'],
                'options_en' => ['3 rooms', '5 rooms', '8 rooms'],
                'correct_index' => 1,
            ],
            [
                'question_vi' => 'GM Room là phòng của ai?',
                'question_en' => 'Who uses the GM Room?',
                'options' => ['Nhân viên', 'Giám đốc', 'Khách hàng'],
                'options_en' => ['Employees', 'General Manager', 'Clients'],
                'correct_index' => 1,
            ],
            [
                'question_vi' => 'Novo Nordisk hoạt động ở bao nhiêu quốc gia?',
                'question_en' => 'In how many countries does Novo Nordisk operate?',
                'options' => ['Hơn 80', 'Dưới 20', 'Đúng 50'],
                'options_en' => ['Over 80', 'Under 20', 'Exactly 50'],
                'correct_index' => 0,
            ],
        ];

        foreach ($questions as $question) {
            QuizQuestion::create(array_merge($question, ['is_active' => true]));
        }
    }
}
