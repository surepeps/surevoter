-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 21, 2023 at 03:45 PM
-- Server version: 5.7.23-23
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `surecode_vote`
--

-- --------------------------------------------------------

--
-- Table structure for table `bs_action_log`
--

CREATE TABLE `bs_action_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `page` varchar(100) NOT NULL,
  `action_description` text NOT NULL,
  `status` varchar(50) NOT NULL,
  `action_type` varchar(50) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--
-- Table structure for table `bs_admins`
--

CREATE TABLE `bs_admins` (
  `admin_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(150) NOT NULL,
  `password` text NOT NULL,
  `phone_number` varchar(50) NOT NULL,
  `status` int(11) NOT NULL,
  `ip_address` text NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bs_admins`
--

INSERT INTO `bs_admins` (`admin_id`, `name`, `username`, `password`, `phone_number`, `status`, `ip_address`, `date_created`) VALUES
(1, 'Admin', 'surecoder', '$2y$10$t9Rtzxzwv.hZ2GZRZ7kdhu/0ixYZo4ZD/imdMPPypO40STEuDb8Bq', '08170513546', 1, '105.112.155.209', '2023-01-12 14:50:35');

-- --------------------------------------------------------

--
-- Table structure for table `bs_appssessions`
--

CREATE TABLE `bs_appssessions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `session_id` varchar(120) NOT NULL,
  `platform` varchar(32) NOT NULL,
  `platform_details` text NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `bs_bad_login`
--

CREATE TABLE `bs_bad_login` (
  `id` int(11) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `time` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `bs_banned_ip`
--

CREATE TABLE `bs_banned_ip` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(32) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `bs_candidates`
--

CREATE TABLE `bs_candidates` (
  `candidate_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `winning` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `bs_config`
--

CREATE TABLE `bs_config` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bs_config`
--

INSERT INTO `bs_config` (`id`, `name`, `value`) VALUES
(1, 'english', '1'),
(2, 'arabic', '1'),
(3, 'dutch', '1'),
(4, 'french', '1'),
(5, 'german', '1'),
(6, 'italian', '1'),
(7, 'portuguese', '1'),
(8, 'russian', '1'),
(9, 'spanish', '1'),
(10, 'turkish', '1'),
(11, 'siteName', ' Momudu University'),
(12, 'siteTitle', 'Sure Voter'),
(13, 'siteKeywords', 'site, vote, sure'),
(14, 'siteDesc', 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Nihil nisi repellendus distinctio fugit, dolores facilis modi ab exercitationem excepturi perferendis.'),
(15, 'siteEmail', 'yes@yes.com'),
(16, 'defualtLang', 'english'),
(17, 'emailValidation', '1'),
(18, 'theme', 'vote_me'),
(19, 'site_url', ''),
(20, 'prevent_system', '1'),
(21, 'bad_login_limit', '23'),
(22, 'lock_time', '23'),
(23, 'hash_id', '917863be130a113a71420a2df29f2293c0a15a41'),
(24, 'user_registration', '1'),
(25, 'user_limit', '120'),
(26, 'login_auth', '1'),
(30, 'two_factor_type', 'email'),
(32, 'maintenance_mode', '1'),
(33, 'useSeoFrindly', '1'),
(34, 'developers_page', '1'),
(35, 'profile_privacy', '1'),
(36, 'smtp_or_mail', 'mail'),
(37, 'smtp_host', ''),
(38, 'smtp_username', ''),
(39, 'smtp_password', ''),
(40, 'smtp_port', '587'),
(41, 'login_type_system', '0'),
(42, 'register_type_system', '0'),
(43, 'password_complexity_system', '0'),
(44, 'login_auth', '1'),
(45, 'smooth_loading', '0'),
(46, 'user_must_login_activities', '1'),
(47, 'product_rating_system', '1'),
(48, 'fileSharing', '1'),
(49, 'allowedExtenstion', 'jpg,png,jpeg,gif,mkv,docx,zip,rar,pdf,doc,docx,xls,xlsx,pptx,csv,mp3,mp4,flv,wav,txt,mov,avi,webm,wav,mpeg,ppt'),
(50, 'maxUpload', '96000000'),
(51, 'mime_types', 'text/plain,video/mp4,video/mov,video/mpeg,video/flv,video/avi,video/webm,audio/wav,audio/mpeg,video/quicktime,audio/mp3,image/png,image/jpeg,image/gif,application/pdf,application/msword,application/zip,application/x-rar-compressed,text/pdf,application/x-pointplus,text/css,application/vnd.openxmlformats-officedocument.wordprocessingml.document,text/csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/vnd.ms-powerpoint,\r\n'),
(52, 'site_logo', 'uploads/photos/2023/01/qRA9qga5sruppHXbrcdd_09_88035ecc40e455ad74042569cfb63fa7_image.png'),
(53, 'category_title', 'Top Categories Of The Month'),
(54, 'category_description', ''),
(57, 'home_banner_title', 'Find an Expert'),
(58, 'home_banner_description', 'Book a Consultation by Appointment, Chat or Video call'),
(59, 'homepage_search_input', '1'),
(60, 'site_footer', 'Copyright © 2023 SureVoter 1345'),
(61, 'banner_image', 'a:3:{i:0;s:26:\"banner_Slider_DISCOVER.jpg\";i:1;s:46:\"banner_Warranty-Guaranted-homepage-des-sli.jpg\";i:2;s:34:\"banner_Homepage-Slider_desktop.jpg\";}'),
(62, 'automatic_approve_reviews', '1'),
(63, 'primary_color', '#6eae95'),
(64, 'secondary_color', '#044a41'),
(65, 'text_color', '#222222'),
(66, 'button_primary_color', '#5a1565'),
(67, 'button_secondary_color', '#f1a24c'),
(81, 'smtp_encryption', 'tls'),
(82, 'sms_or_email', 'email'),
(83, 'facebook', ''),
(84, 'twitter', ''),
(85, 'instagram', ''),
(86, 'youtube', ''),
(87, 'contact_address', ''),
(88, 'contact_email', ''),
(89, 'contact_phone', ''),
(100, 'version', '1.0.1'),
(101, 'cacheSystem', '0'),
(102, 'amazone_s3', ''),
(103, 'amazone_s3_key', ''),
(104, 's3_site_url', ''),
(105, 'spaces', ''),
(106, 'spaces_key', ''),
(107, 'space_name', ''),
(108, 'amazone_s3_s_key', ''),
(109, 'spaces_secret', ''),
(110, 'ftp_upload', ''),
(111, 'ftp_endpoint', ''),
(112, 'cloud_upload', ''),
(113, 'cloud_bucket_name', ''),
(114, 'google_map_api', ''),
(115, 'googleAnalytics', ''),
(116, 'headerScript', ''),
(131, 'googleAnalytics_en', 'dW5kZWZpbmVk'),
(138, 'homeTitle', 'Welcome');

-- --------------------------------------------------------

--
-- Table structure for table `bs_department`
--

CREATE TABLE `bs_department` (
  `department_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `depart_code` varchar(10) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `bs_langs`
--

CREATE TABLE `bs_langs` (
  `id` int(11) NOT NULL,
  `lang_key` varchar(160) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(100) NOT NULL DEFAULT '',
  `english` text,
  `arabic` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `dutch` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `french` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `german` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `italian` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `portuguese` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `russian` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `spanish` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `turkish` text CHARACTER SET utf8 COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bs_langs`
--

INSERT INTO `bs_langs` (`id`, `lang_key`, `type`, `english`, `arabic`, `dutch`, `french`, `german`, `italian`, `portuguese`, `russian`, `spanish`, `turkish`) VALUES
(1, 'login', '', 'Login', 'تسجيل الدخول', 'Inloggen', 'S&#39;identifier', 'Anmelden', 'Entra', 'Login', 'Вход', 'Acceder', 'Giriş'),
(2, 'register', '', 'Register', 'التسجيل', 'Registereren', 'Enregistrez', 'Registrieren', 'Iscriviti', 'Registrar', 'Регистрация', 'Registrar', 'Kayıt'),
(3, 'guest', '', 'Guest', 'زائر', 'Gast', 'Client', 'Gast', 'Ospite', 'Visitante', 'Гость', 'Huésped', 'Konuk'),
(4, 'username', '', 'Username', 'اسم المستخدم', 'Gebruikersnaam', 'le nom d&#39;utilisateur', 'Benutzername', 'Nome Utente', 'Nome de usu&amp;aacute;rio', 'Имя пользователя', 'Nombre de Usuario', 'Kullanıcı adı'),
(5, 'email', '', 'E-mail', 'البريد الإلكتروني', 'E-mail', 'E-mail', 'Email', 'E-mail', 'E-mail', 'E-mail адрес', 'E-mail', 'E-posta'),
(6, 'password', '', 'Password', 'كلمة المرور', 'Wachtwoord', 'Mot de passe', 'Passwort', 'Password', 'Senha', 'Пароль', 'Contraseña', 'Şifre'),
(7, 'new_password', '', 'New password', 'كلمة المرورالجديدة', 'Nieuw wachtwoord', 'Nouveau mot de passe', 'Neues Passwort', 'Nuova password', 'Nova senha', 'Новый пароль', 'Nueva Contraseña', 'Yeni Şifre'),
(8, 'remember_me', '', 'Remember me', 'تذكرني', 'Onthoud mij', 'Souviens-toi de moi', 'Angemeldet bleiben', 'Resta collegato', 'Lembrar', 'Запомнить меня', 'Recordarme', 'Beni hatırla'),
(9, 'or_login_with', '', 'Or login with', 'أو أدخل عن طريق', 'Of login met', 'Ou connectez-vous avec', 'oder Anmeldung mit', 'o entra con', 'Ou ent&amp;atilde;o fa&amp;ccedil;a login por', 'Или войдите через', 'O Acceder con:', 'Ya ile giriş'),
(10, 'forget_password', '', 'Forgot Password?', 'هل نسيت كلمة المرور؟', 'Wachtwoord vergeten?', 'Mot de passe oublié?', 'Passwort Vergessen?', 'Password dimenticata?', 'Esqueceu sua senha?', 'Забыли пароль?', '¿Olvidaste tu Contraseña?', 'Parolanızı unuttunuz mu?'),
(11, 'email_address', '', 'E-mail address', 'البريد الإلكتروني', 'Email', 'E-mail address', 'Emailadresse', 'Indirizo email', 'E-mail', 'E-mail адрес', 'Direcci&amp;oacute; de E-mail', 'E-posta'),
(12, 'confirm_password', '', 'Confirm Password', 'تأكيد كلمة المرور', 'Bevestig wachtwoord', 'Confirmez le mot de passe', 'Passwort bestätigen', 'Conferma Password', 'Confirmar senha', 'Подтвердите Пароль', 'Confirmar Contraseña', 'Şifreyi Onayla'),
(13, 'sorry_page_not_found', '', 'Sorry, page not found!', 'عذراَ , الصفحة المطلوبة غير موجودة .', 'Sorry, pagina niet gevonden!', 'Désolé, page introuvable!', NULL, NULL, NULL, NULL, NULL, NULL),
(14, 'form_name_not_empty', '', 'Form Name can\'t be empty', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(15, 'general_success_message', '', 'Request Successfully Processed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(16, 'general_error_message', '', 'Sorry, System could not process your request try again.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(17, 'general_update_success_message', '', 'Successfully Updated', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(18, 'general_update_error_message', '', 'Sorry system could not update your request', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(19, 'login_attempts', '', 'Too many login attempts please try again later', 'الكثير من محاولات تسجيل الدخول يرجى المحاولة مرة أخرى في وقت لاحق', 'Te veel inlogpogingen, probeer het later opnieuw', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(20, 'incorrect_username_or_password_label', '', 'Incorrect username or password', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(21, 'account_disbaled_contanct_admin_label', '', 'Your account has been disabled, please contact us .', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(22, 'welcome_back', '', 'Welcome back!', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, 'complexity_requirements', '', 'The password supplied does not meet the minimum complexity requirements', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(24, 'reset_new_password_label', '', 'Reset Your Password', 'إعادة تعيين كلمة المرور', 'Reset Je Wachtwoord', 'Réinitialisez votre mot de passe', 'Passwort zurücksetzen', 'Resetta la tua password', 'Redefinir senha', 'Сбросить пароль', 'Reiniciar Contraseña', 'Şifrenizi sıfırlamak'),
(25, 'reset_password', '', 'Reset', 'إعادة تعيين', 'Reset', 'Réinitialiser', 'Zurücksetzen', 'Resetta', 'Resetar', 'Сброс', 'Reiniciar', 'Reset'),
(26, 'password_invalid_characters', '', 'Invalid password characters', 'صيغة كلمة المرور خاطئة', 'Ongeldige tekens in je wachtwoord', 'Caractères de mot de passe invalide', 'Passwort enthält unzulässige Zeichen', 'Caratteri della password non validi', 'Caracteres inv&amp;aacute;lidos', 'Недопустимые символы в пароле', 'Caracteres Inv&amp;aacute;lidos', 'Geçersiz şifre karakteri'),
(27, 'password_short', '', 'Password is too short.', 'كلمة المرور قصيرة جداَ', 'Wachtwoord is te kort.', 'Mot de passe est trop court.', 'Passwort ist zu kurz.', 'La password è troppo corta.', 'Senha muito pequena.', 'Пароль слишком короткий.', 'Contrase&amp;ntilde;a muy corta.', 'Şifre çok kısa.'),
(28, 'password_mismatch', '', 'Password not match.', 'كلمة المرور غير متطابقة', 'Wachtwoorden komen niet overeen.', 'Mot de passe ne correspond.', 'Passwörter stimmen nicht überein.', 'La password non corrisponde.', 'As senhas n&amp;atilde;o conferem.', 'Пароли не совпадают.', 'Contrase&amp;ntilde; diferente.', 'Şifre eşleşmiyor.'),
(29, 'password_rest_request', '', 'Password reset request', 'طلب إعادة تعيين كلمة المرور', 'Wachtwoord reset aanvraag', 'Demande de réinitialisation de mot', 'Passwort-Reset-Anfrage', 'Richiesta di reimpostazione della password', 'Pedido para resetar senha', 'Запрос Восстановление пароля', 'Solicitud de reinicio de contraseña', 'Parola sıfırlama isteği'),
(30, 'password_changed', '', 'Password successfully changed !', 'تم تغيير كلمة المرور بنجاح', 'Wachtwoord succesvol gewijzigd !', 'Mot de passe changé avec succès !', 'Passwort erfolgreich geändert!', 'Password cambiata con successo!', 'Senha trocada com sucesso !', 'Пароль успешно изменен!', '¡ Contrase&amp;ntilde;a modificada correctamente !', 'Şifre başarıyla değiştirildi !'),
(31, 'current_password_mismatch', '', 'Current password not match', 'كلمة المرور الحالية غير صحيحة', 'Huidig wachtwoord komt niet overeen', 'Mot de passe actuel ne correspond pas', 'Aktuelles Passwort stimmt nicht', 'Password corrente non corrisponde', 'Sua senha atual n&amp;atilde;o confere', 'Текущий пароль не совпадает', 'Contrase&amp;ntilde;a actual diferente', 'Mevcut şifre eşleşmiyor'),
(32, 'create_your_new_password', '', 'Create your new password:', 'أنشء كملنة المرور:', 'Geef een nieuw wachtwoord op:', 'Créer votre nouveau mot de passe:', 'Erstelle dein neues Passwort:', 'Crea la tua nuova password:', 'Nova Senha:', 'Создать новый пароль:', 'Crear tu nueva contrase&amp;ntilde;a:', 'Yeni şifrenizi oluşturun:'),
(33, 'change_password', '', 'Change Password', 'تغير كلمة المرور', 'Wijzig Wachtwoord', 'Changer le mot de passe', 'Passwort ändern', 'Cambiare la password', 'Mudar Senha', 'Изменить пароль', 'Cambiar contrase&amp;ntilde;a', 'Şifre değiştir'),
(34, 'current_password', '', 'Current Password', 'كلمة المرور الحالية', 'Huidig wachtwoord', 'Mot de passe actuel', 'Aktuelles Passwort', 'Password attuale', 'Senha Atual', 'Текущий пароль', 'Contrase&amp;ntilde;a actual', 'Şifreniz'),
(35, 'repeat_password', '', 'Repeat password', 'أعادة كلمة المرور', 'Herhaal wachtwoord', 'Répéter le mot de passe', 'Passwort wiederholen', 'Ripeti la password', 'Confirme sua senha atual', 'Повторите пароль', 'Repetir contrase&amp;ntilde;a', 'Şifreyi tekrar girin'),
(36, 'worng_phone_number', '', 'Wrong phone number.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(37, 'limit_exceeded', '', 'Limit exceeded, please try again later.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(38, 'email_provider_banned', 'The email provider is blacklisted and not allowed, please choose another email provider.', 'The email provider is blacklisted and not allowed, please choose another email provider.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(39, 'email_is_banned', '', 'The email address is blacklisted and not allowed, please choose another email.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(40, 'worng_phone_number', '', 'Wrong phone number.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(41, 'phone_already_used', '', 'Phone number already used.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(42, 'email_exists', '', 'This e-mail is already in use', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(43, 'email_invalid_characters', '', 'This e-mail is invalid.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(46, 'successfully_joined_label', '', 'Successfully joined, Please wait..', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(47, 'account_activation', '', 'Account Activation', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(48, 'failed_to_send_code_email', '', 'Error while sending the SMS, please try another number or activate your account via email by logging into your account.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(49, 'contain_lowercase', '', 'Must contain a lowercase letter.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(50, 'least_characters', '', 'Must be at least 6 characters long.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(51, 'contain_uppercase', '', 'Must contain an uppercase letter.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(52, 'number_special', '', 'Must contain a number or special character.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(53, 'username_exists', '', 'Username is already exists.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(54, 'username_is_banned', '', 'The username is blacklisted and not allowed, please choose another username.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(55, 'password_notification', '', 'Password Notification', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(56, 'professional', '', 'Professional', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(57, 'professionals', '', 'ProfessionalS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(58, 'professional_plugin', '', 'Professional Plugin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(59, 'success_create', '', 'Successfully Created', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(60, 'found_already', '', 'Already Exist, Please try new one', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(61, 'general_delete_success_message', '', 'Successfully deleted', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(62, 'user_id_not_valid', '', 'Authentication Error', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(63, 'form_not_created', '', 'Form Not Available Please Try again later', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(64, 'review_error_same_user_id', '', 'Sorry you can\'t make review on your account', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(65, 'review_rating_poor', '', 'Poor', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(66, 'no_review_rating', '', 'No reviews', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(67, 'review_rating_superb', '', 'Superb', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(68, 'review_rating_excellent', '', 'Excellent', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(69, 'sorry_no_reviews', '', 'There is no review for this user', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(70, 'no_record_found', '', 'Sorry no record found try again', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(71, 'contact_message_notification', '', 'New Contact Message', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(72, 'email_notification_message', '', 'Mail Successfully Sent', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(73, 'expert_message_notification', '', 'Expert Access Notification ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(74, 'expert_remove_message_notification', '', 'Expert Access Restriction Notification', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(75, 'please_check_details', '', 'Please check your details.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(76, 'email_not_found', '', 'We can&#039;t find this email.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(77, 'invalid_markup', '', 'Invalid markup, please try to reset your password again', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(78, 'invalid_token', '', 'Invalid Token', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(79, 'processing_error', '', 'An error found while processing your request, please try again later.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(80, 'we_have_sent_you_code', '', 'We have sent you an email with the confirmation code.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(81, 'sent_two_factor_email', '', 'We have sent you the confirmation code to your email address.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(82, 'confirm_your_account', '', 'Confirm your account', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(83, 'sign_in_attempt', '', 'Your sign in attempt seems a little different than usual, This could be because you are signing in from a different device or a different location.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(84, 'to_log_in_two_factor', '', 'To log in, you need to verify your identity.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(85, 'error_while_activating', '', 'Error while activating your account.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(86, 'wrong_confirmation_code', '', 'Wrong confirmation code.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(87, 'email_sent', '', 'Recovery Password Mail successfully Sent', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(88, 'night_mode', '', 'Good Evening', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(89, 'day_mode', '', 'Good Morning', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(90, 'category', '', 'Category', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(91, 'subcategory', '', 'Sub Category', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(92, 'your_account_activated', '', 'Your Account is now activated ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bs_notify_messages`
--

CREATE TABLE `bs_notify_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `bs_posts`
--

CREATE TABLE `bs_posts` (
  `post_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `location` varchar(200) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bs_posts`
--

INSERT INTO `bs_posts` (`post_id`, `title`, `location`, `status`) VALUES
(1, 'President', 'Faculty of Science', 1),
(2, 'Vice President', 'Faculty of Science', 1),
(3, 'Secretary', 'Faculty of Science', 1),
(4, 'PRO', 'Faculty of Science', 1),
(7, 'Fin Sec', 'Faculty of Science', 1);

-- --------------------------------------------------------

--
-- Table structure for table `bs_users`
--

CREATE TABLE `bs_users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(500) NOT NULL,
  `last_name` varchar(500) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `email` varchar(500) DEFAULT NULL,
  `avatar` varchar(200) NOT NULL DEFAULT '''uploads/photos/d-avatar.jpg	''',
  `last_avatar_mod` varchar(100) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `admin` int(11) NOT NULL DEFAULT '0',
  `department_id` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `vote_status` int(11) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '0',
  `last_login_data` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- --------------------------------------------------------

--
-- Table structure for table `bs_votes`
--

CREATE TABLE `bs_votes` (
  `vote_id` int(11) NOT NULL,
  `voter_id` int(11) NOT NULL,
  `candidate_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `ip_address` varchar(100) NOT NULL,
  `status` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bs_action_log`
--
ALTER TABLE `bs_action_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bs_admins`
--
ALTER TABLE `bs_admins`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `bs_appssessions`
--
ALTER TABLE `bs_appssessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bs_bad_login`
--
ALTER TABLE `bs_bad_login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bs_banned_ip`
--
ALTER TABLE `bs_banned_ip`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bs_candidates`
--
ALTER TABLE `bs_candidates`
  ADD PRIMARY KEY (`candidate_id`);

--
-- Indexes for table `bs_config`
--
ALTER TABLE `bs_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bs_department`
--
ALTER TABLE `bs_department`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `bs_langs`
--
ALTER TABLE `bs_langs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bs_notify_messages`
--
ALTER TABLE `bs_notify_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bs_posts`
--
ALTER TABLE `bs_posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `bs_users`
--
ALTER TABLE `bs_users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `bs_votes`
--
ALTER TABLE `bs_votes`
  ADD PRIMARY KEY (`vote_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bs_action_log`
--
ALTER TABLE `bs_action_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `bs_admins`
--
ALTER TABLE `bs_admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bs_appssessions`
--
ALTER TABLE `bs_appssessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `bs_bad_login`
--
ALTER TABLE `bs_bad_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `bs_banned_ip`
--
ALTER TABLE `bs_banned_ip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `bs_candidates`
--
ALTER TABLE `bs_candidates`
  MODIFY `candidate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `bs_config`
--
ALTER TABLE `bs_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT for table `bs_department`
--
ALTER TABLE `bs_department`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `bs_langs`
--
ALTER TABLE `bs_langs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `bs_notify_messages`
--
ALTER TABLE `bs_notify_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bs_posts`
--
ALTER TABLE `bs_posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `bs_users`
--
ALTER TABLE `bs_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `bs_votes`
--
ALTER TABLE `bs_votes`
  MODIFY `vote_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
