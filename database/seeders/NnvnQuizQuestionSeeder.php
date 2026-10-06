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
            // ========== THE NOVO WAY (Group 1: 1-15) ==========

            // Q1 - Multiple correct (A,B,C,D) → E combo
            [
                'question_vi' => 'Đâu là 4 nguyên tắc của The Novo Way?',
                'question_en' => 'What are the 4 principles of The Novo Way?',
                'options' => ['Customer Obsession', 'Competitiveness', 'Clarity', 'Care & Integrity', 'Tất cả đáp án trên đều đúng'],
                'options_en' => ['Customer Obsession', 'Competitiveness', 'Clarity', 'Care & Integrity', 'All of the above are correct'],
                'correct_index' => 4,
            ],

            // Q2 - Single correct (A)
            [
                'question_vi' => 'Nội dung nào mô tả đúng nguyên tắc Customer Obsession?',
                'question_en' => 'Which statement correctly describes the Customer Obsession principle?',
                'options' => [
                    'Luôn đặt người tiêu dùng và bệnh nhân làm trung tâm để thúc đẩy sự đổi mới trong mọi hoạt động',
                    'Chỉ tập trung vào mục tiêu kinh doanh ngắn hạn',
                    'Ưu tiên giải pháp khoa học dù xa rời thực tế thị trường',
                    'Duy trì cách tiếp cận hiện tại và hạn chế thử nghiệm',
                ],
                'options_en' => [
                    'Always place consumers and patients at the center to drive innovation in all activities',
                    'Focus only on short-term business objectives',
                    'Prioritize scientific solutions even if they are disconnected from market reality',
                    'Maintain current approaches and limit experimentation',
                ],
                'correct_index' => 0,
            ],

            // Q3 - Multiple correct (A,B) → E combo
            [
                'question_vi' => 'Những hành vi nào chúng ta hướng tới theo nguyên tắc Customer Obsession?',
                'question_en' => 'Which behaviors do we aspire to under the Customer Obsession principle?',
                'options' => [
                    'Tìm hiểu khách hàng',
                    'Dám thử những hướng tiếp cận mới',
                    'Thể hiện thái độ tự cho là biết tất cả',
                    'Đề xuất các giải pháp khoa học xa rời thực tế thị trường',
                    'Cả A và B đều đúng',
                ],
                'options_en' => [
                    'Seek to understand customers',
                    'Dare to try new approaches',
                    'Display a know-it-all attitude',
                    'Propose scientific solutions disconnected from market reality',
                    'Both A and B are correct',
                ],
                'correct_index' => 4,
            ],

            // Q4 - Multiple correct (A,B) → E combo
            [
                'question_vi' => 'Những hành vi nào chúng ta cần từ bỏ theo nguyên tắc Customer Obsession?',
                'question_en' => 'Which behaviors should we let go of under the Customer Obsession principle?',
                'options' => [
                    'Thể hiện thái độ tự cho là biết tất cả',
                    'Đề xuất các giải pháp khoa học xa rời thực tế thị trường',
                    'Tìm hiểu khách hàng',
                    'Dám thử những hướng tiếp cận mới',
                    'Cả A và B đều đúng',
                ],
                'options_en' => [
                    'Display a know-it-all attitude',
                    'Propose scientific solutions disconnected from market reality',
                    'Seek to understand customers',
                    'Dare to try new approaches',
                    'Both A and B are correct',
                ],
                'correct_index' => 4,
            ],

            // Q5 - Single correct (A)
            [
                'question_vi' => 'Nội dung nào mô tả đúng nguyên tắc Competitiveness?',
                'question_en' => 'Which statement correctly describes the Competitiveness principle?',
                'options' => [
                    'Nâng cao hiệu suất để tạo thêm nhiều giá trị cho tất cả các bên liên quan',
                    'Duy trì mức hiệu suất hiện tại',
                    'Ưu tiên thành tích cũ hơn hiệu suất hiện tại',
                    'Giữ nguyên cách làm hiện tại dù có lựa chọn tốt hơn',
                ],
                'options_en' => [
                    'Elevate performance to create more value for all stakeholders',
                    'Maintain the current performance level',
                    'Prioritize past achievements over current performance',
                    'Stick with current methods even when better options exist',
                ],
                'correct_index' => 0,
            ],

            // Q6 - Multiple correct (A,B) → E combo
            [
                'question_vi' => 'Những hành vi nào chúng ta hướng tới theo nguyên tắc Competitiveness?',
                'question_en' => 'Which behaviors do we aspire to under the Competitiveness principle?',
                'options' => [
                    'Nỗ lực phát triển và nâng cao tiêu chuẩn mỗi ngày',
                    'Đặt những mục tiêu tham vọng để chiến thắng',
                    'Khen thưởng dựa trên thành tích cũ thay vì hiệu suất hiện tại',
                    'Duy trì "cách chúng ta vẫn làm" dù có những lựa chọn tốt hơn',
                    'Cả A và B đều đúng',
                ],
                'options_en' => [
                    'Strive to grow and raise standards every day',
                    'Set ambitious goals to win',
                    'Reward based on past achievements rather than current performance',
                    'Maintain "the way we\'ve always done it" even when better options exist',
                    'Both A and B are correct',
                ],
                'correct_index' => 4,
            ],

            // Q7 - Multiple correct (A,B) → E combo
            [
                'question_vi' => 'Những hành vi nào chúng ta cần từ bỏ theo nguyên tắc Competitiveness?',
                'question_en' => 'Which behaviors should we let go of under the Competitiveness principle?',
                'options' => [
                    'Khen thưởng dựa trên thành tích cũ thay vì hiệu suất hiện tại',
                    'Duy trì "cách chúng ta vẫn làm" dù có những lựa chọn tốt hơn',
                    'Nỗ lực phát triển và nâng cao tiêu chuẩn mỗi ngày',
                    'Đặt những mục tiêu tham vọng để chiến thắng',
                    'Cả A và B đều đúng',
                ],
                'options_en' => [
                    'Reward based on past achievements rather than current performance',
                    'Maintain "the way we\'ve always done it" even when better options exist',
                    'Strive to grow and raise standards every day',
                    'Set ambitious goals to win',
                    'Both A and B are correct',
                ],
                'correct_index' => 4,
            ],

            // Q8 - Single correct (A)
            [
                'question_vi' => 'Nội dung nào mô tả đúng nguyên tắc Clarity?',
                'question_en' => 'Which statement correctly describes the Clarity principle?',
                'options' => [
                    'Tạo sự tập trung và tốc độ thông qua các ưu tiên rõ ràng, cách làm việc đơn giản và hành động quyết đoán',
                    'Tham vấn càng nhiều người càng tốt trước mọi quyết định',
                    'Ưu tiên lợi ích của phạm vi phụ trách hơn lợi ích của toàn công ty',
                    'Trì hoãn hành động cho đến khi đạt được sự đồng thuận tuyệt đối',
                ],
                'options_en' => [
                    'Create focus and speed through clear priorities, simple ways of working, and decisive action',
                    'Consult as many people as possible before every decision',
                    'Prioritize the interests of your own area over the interests of the entire company',
                    'Delay action until absolute consensus is reached',
                ],
                'correct_index' => 0,
            ],

            // Q9 - Multiple correct (A,B) → E combo
            [
                'question_vi' => 'Những hành vi nào chúng ta hướng tới theo nguyên tắc Clarity?',
                'question_en' => 'Which behaviors do we aspire to under the Clarity principle?',
                'options' => [
                    'Ra quyết định, chịu trách nhiệm và theo sát đến khi hoàn tất',
                    'Lên tiếng khi công việc có dấu hiệu bị đình trệ',
                    'Tham vấn quá nhiều người vào các quyết định để giữ sự hài hòa',
                    'Đặt lợi ích của phạm vi phụ trách lên trên lợi ích của toàn công ty',
                    'Cả A và B đều đúng',
                ],
                'options_en' => [
                    'Make decisions, take ownership, and follow through to completion',
                    'Speak up when work shows signs of stalling',
                    'Consult too many people on decisions to maintain harmony',
                    'Put the interests of your own area above the interests of the entire company',
                    'Both A and B are correct',
                ],
                'correct_index' => 4,
            ],

            // Q10 - Multiple correct (A,B) → E combo
            [
                'question_vi' => 'Những hành vi nào chúng ta cần từ bỏ theo nguyên tắc Clarity?',
                'question_en' => 'Which behaviors should we let go of under the Clarity principle?',
                'options' => [
                    'Tham vấn quá nhiều người vào các quyết định để giữ sự hài hòa',
                    'Đặt lợi ích của phạm vi phụ trách lên trên lợi ích của toàn công ty',
                    'Ra quyết định, chịu trách nhiệm và theo sát đến khi hoàn tất',
                    'Lên tiếng khi công việc có dấu hiệu bị đình trệ',
                    'Cả A và B đều đúng',
                ],
                'options_en' => [
                    'Consult too many people on decisions to maintain harmony',
                    'Put the interests of your own area above the interests of the entire company',
                    'Make decisions, take ownership, and follow through to completion',
                    'Speak up when work shows signs of stalling',
                    'Both A and B are correct',
                ],
                'correct_index' => 4,
            ],

            // Q11 - Single correct (A)
            [
                'question_vi' => 'Nội dung nào mô tả đúng nguyên tắc Care & Integrity?',
                'question_en' => 'Which statement correctly describes the Care & Integrity principle?',
                'options' => [
                    'Quan tâm đến con người, thể hiện sự tôn trọng và không bao giờ thỏa hiệp về an toàn bệnh nhân cũng như các chuẩn mực đạo đức',
                    'Tránh các cuộc đối thoại thẳng thắn để duy trì sự hài hòa',
                    'Không chấp nhận bất kỳ rủi ro nào, kể cả rủi ro đã được cân nhắc kỹ',
                    'Theo đuổi sự hoàn hảo thay vì ưu tiên tạo ra tác động',
                ],
                'options_en' => [
                    'Care about people, show respect, and never compromise on patient safety or ethical standards',
                    'Avoid candid conversations to maintain harmony',
                    'Accept no risks whatsoever, including well-considered ones',
                    'Pursue perfection rather than prioritizing impact',
                ],
                'correct_index' => 0,
            ],

            // Q12 - Multiple correct (A,B) → E combo
            [
                'question_vi' => 'Những hành vi nào chúng ta hướng tới theo nguyên tắc Care & Integrity?',
                'question_en' => 'Which behaviors do we aspire to under the Care & Integrity principle?',
                'options' => [
                    'Chủ động trao đổi và tiếp nhận phản hồi trung thực',
                    'Ưu tiên tạo ra tác động hơn là theo đuổi sự hoàn hảo',
                    'Né tránh những cuộc đối thoại thẳng thắn để duy trì sự hài hòa với đồng nghiệp',
                    'Quá thận trọng đến mức không dám chấp nhận những rủi ro đã được cân nhắc kỹ',
                    'Cả A và B đều đúng',
                ],
                'options_en' => [
                    'Proactively give and receive honest feedback',
                    'Prioritize creating impact over pursuing perfection',
                    'Avoid candid conversations to maintain harmony with colleagues',
                    'Be so cautious that you do not dare to take well-considered risks',
                    'Both A and B are correct',
                ],
                'correct_index' => 4,
            ],

            // Q13 - Multiple correct (A,B) → E combo
            [
                'question_vi' => 'Những hành vi nào chúng ta cần từ bỏ theo nguyên tắc Care & Integrity?',
                'question_en' => 'Which behaviors should we let go of under the Care & Integrity principle?',
                'options' => [
                    'Né tránh những cuộc đối thoại thẳng thắn vì muốn duy trì sự hài hòa với đồng nghiệp',
                    'Quá thận trọng đến mức không dám chấp nhận những rủi ro đã được cân nhắc kỹ',
                    'Chủ động trao đổi và tiếp nhận phản hồi trung thực',
                    'Ưu tiên tạo ra tác động hơn là theo đuổi sự hoàn hảo',
                    'Cả A và B đều đúng',
                ],
                'options_en' => [
                    'Avoid candid conversations to maintain harmony with colleagues',
                    'Be so cautious that you do not dare to take well-considered risks',
                    'Proactively give and receive honest feedback',
                    'Prioritize creating impact over pursuing perfection',
                    'Both A and B are correct',
                ],
                'correct_index' => 4,
            ],

            // Q14 - Multiple correct (A,B,C,D) → E combo
            [
                'question_vi' => 'Cặp nguyên tắc – mô tả nào dưới đây là chính xác?',
                'question_en' => 'Which principle-description pair below is correct?',
                'options' => [
                    'Customer Obsession – Đặt người tiêu dùng và bệnh nhân làm trung tâm để thúc đẩy đổi mới',
                    'Competitiveness – Nâng cao hiệu suất để tạo thêm giá trị cho các bên liên quan',
                    'Clarity – Tạo sự tập trung và tốc độ qua ưu tiên rõ ràng, cách làm việc đơn giản và hành động quyết đoán',
                    'Care & Integrity – Quan tâm đến con người, thể hiện sự tôn trọng và không thỏa hiệp về an toàn bệnh nhân và đạo đức',
                    'Tất cả đáp án trên đều đúng',
                ],
                'options_en' => [
                    'Customer Obsession – Place consumers and patients at the center to drive innovation',
                    'Competitiveness – Elevate performance to create more value for stakeholders',
                    'Clarity – Create focus and speed through clear priorities, simple ways of working, and decisive action',
                    'Care & Integrity – Care about people, show respect, and never compromise on patient safety or ethics',
                    'All of the above are correct',
                ],
                'correct_index' => 4,
            ],

            // Q15 - Single correct (A)
            [
                'question_vi' => 'Khi công việc có dấu hiệu bị đình trệ, hành vi nào phù hợp nhất với nguyên tắc Clarity?',
                'question_en' => 'When work shows signs of stalling, which behavior best aligns with the Clarity principle?',
                'options' => [
                    'Lên tiếng kịp thời',
                    'Chờ thêm để tránh tạo áp lực',
                    'Tham vấn thêm nhiều người để giữ sự hài hòa',
                    'Chỉ tập trung vào phần việc thuộc phạm vi phụ trách',
                ],
                'options_en' => [
                    'Speak up in a timely manner',
                    'Wait longer to avoid creating pressure',
                    'Consult more people to maintain harmony',
                    'Focus only on the work within your own scope',
                ],
                'correct_index' => 0,
            ],

            // ========== NHẬN DIỆN THƯƠNG HIỆU NOVO (Group 2: 16-20) ==========

            // Q16 - Single correct (A)
            [
                'question_vi' => 'Sứ mệnh cốt lõi định hướng mọi hoạt động của Novo là gì?',
                'question_en' => 'What is the core mission that guides all of Novo\'s activities?',
                'options' => [
                    'Driving change for lasting health',
                    'Lasting Health Starts Now',
                    'Science for a better tomorrow',
                    'Innovation for everyone',
                ],
                'options_en' => [
                    'Driving change for lasting health',
                    'Lasting Health Starts Now',
                    'Science for a better tomorrow',
                    'Innovation for everyone',
                ],
                'correct_index' => 0,
            ],

            // Q17 - Single correct (B)
            [
                'question_vi' => 'Cam kết thương hiệu của Novo là gì?',
                'question_en' => 'What is Novo\'s brand promise?',
                'options' => [
                    'Driving change for lasting health',
                    'Lasting Health Starts Now',
                    'Health for every generation',
                    'Change begins with science',
                ],
                'options_en' => [
                    'Driving change for lasting health',
                    'Lasting Health Starts Now',
                    'Health for every generation',
                    'Change begins with science',
                ],
                'correct_index' => 1,
            ],

            // Q18 - Single correct (B)
            [
                'question_vi' => 'Thông điệp "Lasting Health Starts Now" phản ánh niềm tin nào của Novo?',
                'question_en' => 'What belief of Novo does the message "Lasting Health Starts Now" reflect?',
                'options' => [
                    'Sức khỏe bền vững chỉ có thể đạt được trong tương lai xa',
                    'Hành trình hướng tới sức khỏe bền vững cần bắt đầu ngay hôm nay qua những thay đổi tích cực có thể thấy và cảm nhận mỗi ngày',
                    'Chỉ các đổi mới quy mô lớn mới tạo ra sức khỏe bền vững',
                    'Sức khỏe bền vững phụ thuộc hoàn toàn vào tiến bộ khoa học',
                ],
                'options_en' => [
                    'Lasting health can only be achieved in the distant future',
                    'The journey toward lasting health must start today through positive changes that can be seen and felt every day',
                    'Only large-scale innovations can create lasting health',
                    'Lasting health depends entirely on scientific progress',
                ],
                'correct_index' => 1,
            ],

            // Q19 - Single correct (D)
            [
                'question_vi' => 'Thương hiệu Novo tiếp tục kế thừa và phát triển trên những nền tảng nào?',
                'question_en' => 'On which foundations does the Novo brand continue to inherit and develop?',
                'options' => [
                    'Khoa học',
                    'Sự tận tâm',
                    'Tính chính trực',
                    'Tất cả các đáp án trên',
                ],
                'options_en' => [
                    'Science',
                    'Dedication',
                    'Integrity',
                    'All of the above',
                ],
                'correct_index' => 3,
            ],

            // Q20 - Single correct (A)
            [
                'question_vi' => 'Cách sử dụng tên gọi nào sau đây là chính xác?',
                'question_en' => 'Which of the following naming conventions is correct?',
                'options' => [
                    'Sử dụng tên Novo trong các hoạt động thường nhật, truyền thông và xây dựng thương hiệu; pháp nhân hoạt động vẫn là Novo Nordisk Việt Nam',
                    'Thay toàn bộ tên pháp nhân Novo Nordisk Việt Nam thành Novo',
                    'Chỉ sử dụng tên Novo trong truyền thông bên ngoài',
                    'Tiếp tục dùng Novo Nordisk trong mọi hoạt động thường nhật và xây dựng thương hiệu',
                ],
                'options_en' => [
                    'Use the name Novo in daily activities, communications, and branding; the operating legal entity remains Novo Nordisk Vietnam',
                    'Replace the entire legal entity name Novo Nordisk Vietnam with Novo',
                    'Use the name Novo only in external communications',
                    'Continue using Novo Nordisk in all daily activities and branding',
                ],
                'correct_index' => 0,
            ],

            // ========== OZEMPIC (Group 3: 21-24) ==========

            // Q21 - Single correct (A)
            [
                'question_vi' => 'Hoạt chất của Ozempic là:',
                'question_en' => 'The active ingredient of Ozempic is:',
                'options' => ['Semaglutide', 'Insulin Aspart', 'Insulin Degludec', 'Metformin'],
                'options_en' => ['Semaglutide', 'Insulin Aspart', 'Insulin Degludec', 'Metformin'],
                'correct_index' => 0,
            ],

            // Q22 - Single correct (A)
            [
                'question_vi' => 'Ozempic thuộc nhóm:',
                'question_en' => 'Ozempic belongs to the class:',
                'options' => ['GLP-1 RA', 'Insulin nền', 'DPP-4 inhibitor', 'Sulfonylurea'],
                'options_en' => ['GLP-1 RA', 'Basal insulin', 'DPP-4 inhibitor', 'Sulfonylurea'],
                'correct_index' => 0,
            ],

            // Q23 - Single correct (A)
            [
                'question_vi' => 'Ozempic được chỉ định điều trị:',
                'question_en' => 'Ozempic is indicated for the treatment of:',
                'options' => ['Đái tháo đường típ 2', 'Béo phì', 'Đái tháo đường típ 1', 'Tăng huyết áp'],
                'options_en' => ['Type 2 diabetes', 'Obesity', 'Type 1 diabetes', 'Hypertension'],
                'correct_index' => 0,
            ],

            // Q24 - Multiple correct (A,B,C) → E combo
            [
                'question_vi' => 'Ozempic đóng vai trò trong:',
                'question_en' => 'Ozempic plays a role in:',
                'options' => [
                    'Kiểm soát đường huyết',
                    'Bảo vệ tim mạch thận',
                    'Hỗ trợ kiểm soát cân nặng',
                    'Điều trị ung thư',
                    'Cả A, B và C đều đúng',
                ],
                'options_en' => [
                    'Blood sugar control',
                    'Cardiovascular and renal protection',
                    'Supporting weight management',
                    'Cancer treatment',
                    'All of A, B, and C are correct',
                ],
                'correct_index' => 4,
            ],

            // ========== WEGOVY (Group 3: 25-28) ==========

            // Q25 - Single correct (A)
            [
                'question_vi' => 'Hoạt chất của Wegovy là:',
                'question_en' => 'The active ingredient of Wegovy is:',
                'options' => ['Semaglutide', 'Aspart', 'Degludec', 'Liraglutide'],
                'options_en' => ['Semaglutide', 'Aspart', 'Degludec', 'Liraglutide'],
                'correct_index' => 0,
            ],

            // Q26 - Single correct (A)
            [
                'question_vi' => 'Wegovy thuộc nhóm:',
                'question_en' => 'Wegovy belongs to the class:',
                'options' => ['GLP-1 RA', 'Insulin nền', 'Insulin trộn sẵn', 'DPP-4 inhibitor'],
                'options_en' => ['GLP-1 RA', 'Basal insulin', 'Pre-mixed insulin', 'DPP-4 inhibitor'],
                'correct_index' => 0,
            ],

            // Q27 - Single correct (A)
            [
                'question_vi' => 'Wegovy được biết đến với chỉ định chính là:',
                'question_en' => 'Wegovy is primarily known for its indication in:',
                'options' => ['Quản lý cân nặng', 'Điều trị COPD', 'Điều trị tăng huyết áp', 'Điều trị hen'],
                'options_en' => ['Weight management', 'COPD treatment', 'Hypertension treatment', 'Asthma treatment'],
                'correct_index' => 0,
            ],

            // Q28 - Multiple correct (A,B,C) → E combo
            [
                'question_vi' => 'Với Wegovy, Novo đóng vai trò trong:',
                'question_en' => 'With Wegovy, Novo plays a role in:',
                'options' => [
                    'Quản lý cân nặng và tác động của béo phì trên bệnh đồng mắc',
                    'Nâng cao nhận thức về béo phì',
                    'Điều trị đái tháo đường',
                    'Điều trị ung thư',
                    'Cả A, B và C đều đúng',
                ],
                'options_en' => [
                    'Weight management and the impact of obesity on comorbidities',
                    'Raising awareness about obesity',
                    'Diabetes treatment',
                    'Cancer treatment',
                    'All of A, B, and C are correct',
                ],
                'correct_index' => 4,
            ],

            // ========== RYZODEG (Group 3: 29-32) ==========

            // Q29 - Single correct (A)
            [
                'question_vi' => 'Ryzodeg là:',
                'question_en' => 'Ryzodeg is:',
                'options' => ['Thuốc insulin', 'GLP-1 RA', 'Kháng sinh', 'Thuốc giảm đau'],
                'options_en' => ['An insulin medication', 'GLP-1 RA', 'An antibiotic', 'A painkiller'],
                'correct_index' => 0,
            ],

            // Q30 - Single correct (D)
            [
                'question_vi' => 'Ryzodeg chứa:',
                'question_en' => 'Ryzodeg contains:',
                'options' => ['Insulin Degludec', 'Insulin Aspart', 'Semaglutide', 'Cả A và B'],
                'options_en' => ['Insulin Degludec', 'Insulin Aspart', 'Semaglutide', 'Both A and B'],
                'correct_index' => 3,
            ],

            // Q31 - Multiple correct (A,B,D) → E combo
            [
                'question_vi' => 'Insulin degludec là:',
                'question_en' => 'Insulin degludec is:',
                'options' => [
                    'Insulin nền tác dụng kéo dài',
                    'Thành phần của Ryzodeg',
                    'GLP-1 RA',
                    'Kiểm soát đường huyết nền',
                    'Cả A, B và D đều đúng',
                ],
                'options_en' => [
                    'A long-acting basal insulin',
                    'A component of Ryzodeg',
                    'GLP-1 RA',
                    'Controls basal blood sugar',
                    'All of A, B, and D are correct',
                ],
                'correct_index' => 4,
            ],

            // Q32 - Multiple correct (A,B,C) → E combo
            [
                'question_vi' => 'Insulin aspart là:',
                'question_en' => 'Insulin aspart is:',
                'options' => [
                    'Insulin tác dụng nhanh',
                    'Thành phần của Ryzodeg',
                    'Hỗ trợ kiểm soát glucose sau ăn',
                    'GLP-1 RA',
                    'Cả A, B và C đều đúng',
                ],
                'options_en' => [
                    'A rapid-acting insulin',
                    'A component of Ryzodeg',
                    'Helps control postprandial glucose',
                    'GLP-1 RA',
                    'All of A, B, and C are correct',
                ],
                'correct_index' => 4,
            ],

            // ========== MISC (Group 3-4: 33-35) ==========

            // Q33 - Single correct (A)
            [
                'question_vi' => 'Ngày Chuyển đổi số quốc gia của Việt Nam được tổ chức vào ngày nào hằng năm?',
                'question_en' => 'On which date is Vietnam\'s National Digital Transformation Day held annually?',
                'options' => ['Ngày 10 tháng 10', 'Ngày 10 tháng 11', 'Ngày 22 tháng 4', 'Ngày 2 tháng 9'],
                'options_en' => ['October 10', 'November 10', 'April 22', 'September 2'],
                'correct_index' => 0,
            ],

            // Q34 - Single correct (A)
            [
                'question_vi' => 'Nhóm hành vi nào dưới đây đều phù hợp với The Novo Way?',
                'question_en' => 'Which group of behaviors below are all aligned with The Novo Way?',
                'options' => [
                    'Tìm hiểu khách hàng; nâng cao tiêu chuẩn mỗi ngày; lên tiếng khi công việc bị đình trệ; chủ động trao đổi phản hồi trung thực',
                    'Tự cho là biết tất cả; duy trì cách làm cũ; tham vấn quá nhiều người; né tránh đối thoại thẳng thắn',
                    'Đề xuất giải pháp xa rời thực tế; dựa vào thành tích cũ; ưu tiên lợi ích cục bộ; không chấp nhận rủi ro đã cân nhắc',
                    'Tất cả các đáp án trên',
                ],
                'options_en' => [
                    'Seek to understand customers; raise standards every day; speak up when work stalls; proactively give and receive honest feedback',
                    'Act like a know-it-all; stick with old methods; consult too many people; avoid candid conversations',
                    'Propose solutions disconnected from reality; rely on past achievements; prioritize local interests; refuse to take well-considered risks',
                    'All of the above',
                ],
                'correct_index' => 0,
            ],

            // Q35 - Single correct (A)
            [
                'question_vi' => 'Đâu là cách gọi tiếng Việt tương ứng của 4 nguyên tắc The Novo Way trong tài liệu nguồn?',
                'question_en' => 'What are the corresponding Vietnamese names of the 4 principles of The Novo Way in the source document?',
                'options' => [
                    'Tận tâm với khách hàng; Tinh thần cạnh tranh; Sự rõ ràng; Quan tâm & Chính trực',
                    'Khách hàng là trên hết; Chiến thắng; Minh bạch; Hợp tác',
                    'Đổi mới; Tác động; Lấy bệnh nhân làm trung tâm; Khoa học',
                    'Tập trung; Tốc độ; Hiệu suất; Tuân thủ',
                ],
                'options_en' => [
                    'Customer Dedication; Competitive Spirit; Clarity; Care & Integrity',
                    'Customer First; Winning; Transparency; Collaboration',
                    'Innovation; Impact; Patient-Centricity; Science',
                    'Focus; Speed; Performance; Compliance',
                ],
                'correct_index' => 0,
            ],
        ];

        foreach ($questions as $question) {
            QuizQuestion::create(array_merge($question, ['is_active' => true]));
        }
    }
}
