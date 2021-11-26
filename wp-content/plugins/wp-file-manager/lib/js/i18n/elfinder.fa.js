/**
 * Persian-Farsi Translation
 * @author Keyhan Mohammadpour <keyhan_universityworks@yahoo.com>
 * @author Farhad Zare <farhad@persianoc.com>
 * @version 2018-07-29
 */
(function(root, factory) {
	if (typeof define === 'function' && define.amd) {
		define(['elfinder'], factory);
	} else if (typeof exports !== 'undefined') {
		module.exports = factory(require('elfinder'));
	} else {
		factory(root.elFinder);
	}
}(this, function(elFinder) {
	elFinder.prototype.i18.fa = {
		translator : 'Keyhan Mohammadpour &lt;keyhan_universityworks@yahoo.com&gt;, Farhad Zare &lt;farhad@persianoc.com&gt;',
		language   : 'فارسی',
		direction  : 'rtl',
		dateFormat : 'd.m.Y H:i',
		fancyDateFormat : '$1 H:i',
		nonameDateFormat : 'ymd-His',
		messages   : {

			/********************************** errors **********************************/
			'error'                : 'خطا',
			'errUnknown'           : 'خطای ناشناخته.',
			'errUnknownCmd'        : 'دستور ناشناخته.',
			'errJqui'              : 'تنظیمات کتابخانه JQuery UI شما به درستی انجام نشده است. این کتابخانه بایستی شامل Resizable ،Draggable و Droppable باشد.',
			'errNode'              : 'elfinder به درستی ایجاد نشده است.',
			'errURL'               : 'تنظیمات elfinder شما به درستی انجام نشده است. تنظیم Url را اصلاح نمایید.',
			'errAccess'            : 'محدودیت سطح دسترسی',
			'errConnect'           : 'امکان اتصال به Backend وجود ندارد.',
			'errAbort'             : 'ارتباط قطع شده است.',
			'errTimeout'           : 'مهلت زمانی Connection شما به اتمام رسیده است.',
			'errNotFound'          : 'تنظیم Backend یافت نشد.',
			'errResponse'          : 'پاسخ دریافتی از Backend صحیح نمی باشد.',
			'errConf'              : 'تنطیمات Backend به درستی انجام نشده است.',
			'errJSON'              : 'ماژول PHP JSON نصب نیست.',
			'errNoVolumes'         : 'درایوهای قابل خواندن یافت نشدند.',
			'errCmdParams'         : 'پارامترهای دستور "$1" به صورت صحیح ارسال نشده است.',
			'errDataNotJSON'       : 'داده ها در قالب JSON نمی باشند.',
			'errDataEmpty'         : 'داده دریافتی تهی است.',
			'errCmdReq'            : 'درخواست از سمت Backend نیازمند نام دستور می باشد.',
			'errOpen'              : 'امکان باز نمودن "$1" وجود ندارد.',
			'errNotFolder'         : 'آیتم موردنظر پوشه نیست.',
			'errNotFile'           : 'آیتم موردنظر فایل نیست.',
			'errRead'              : 'امکان خواندن "$1" وجود ندارد.',
			'errWrite'             : 'امکان نوشتن در درون "$1" وجود ندارد.',
			'errPerm'              : 'شما مجاز به انجام این عمل نمی باشید.',
			'errLocked'            : '"$1" قفل گردیده است و شما قادر به تغییر نام ، حذف و یا جابجایی آن نمی باشید.',
			'errExists'            : 'فایلی با نام "$1" هم اکنون وجود دارد.',
			'errInvName'           : 'نام انتخابی شما صحیح نمی باشد.',
			'errInvDirname'        : 'نام پوشه غیرمعتبر می باشد.',
			'errFolderNotFound'    : 'پوشه مورد نظر یافت نشد.',
			'errFileNotFound'      : 'فایل مورد نظر یافت نشد.',
			'errTrgFolderNotFound' : 'پوشه مقصد با نام "$1" یافت نشد.',
			'errPopup'             : 'مرورگر شما ار باز شدن پنجره popup جلوگیری می کند، لطفا تنظیمات مربوطه را در مرورگر خود فعال نمایید.',
			'errMkdir'             : 'امکان ایجاد پوشه ای با نام "$1" وجود ندارد.',
			'errMkfile'            : 'امکان ایجاد فایلی با نام "$1" وجود ندارد.',
			'errRename'            : 'امکان تغییر نام فایل "$1" وجود ندارد.',
			'errCopyFrom'          : 'کپی نمودن از درایو با نام "$1" ممکن نمی باشد.',
			'errCopyTo'            : 'کپی نمودن به درایو با نام "$1" ممکن نمی باشد.',
			'errMkOutLink'         : 'امکان ایجاد لینک به خارج از مسیر ریشه وجود ندارد.',
			'errUpload'            : 'خطای آپلود',
			'errUploadFile'        : 'امکان آپلود "$1" وجود ندارد.',
			'errUploadNoFiles'     : 'فایلی برای آپلود یافت نشد.',
			'errUploadTotalSize'   : 'حجم داده بیش از حد مجاز می باشد.',
			'errUploadFileSize'    : 'حجم فایل بیش از حد مجاز می باشد.',
			'errUploadMime'        : 'نوع فایل انتخابی مجاز نمی باشد.',
			'errUploadTransfer'    : 'در انتقال "$1" خطایی رخ داده است.',
			'errUploadTemp'        : 'امکان ایجاد فایل موقت جهت آپلود وجود ندارد.',
			'errNotReplace'        : 'آیتم "$1" از قبل وجود دارد و امکان جایگزینی آن با آیتمی از نوع دیگر وجود ندارد.',
			'errReplace'           : 'امکان جایگزینی "$1" وجود ندارد.',
			'errSave'              : 'امکان ذخیره کردن "$1" وجود ندارد.',
			'errCopy'              : 'امکان کپی کردن "$1" وجود ندارد.',
			'errMove'              : 'امکان جابجایی "$1" وجود ندارد.',
			'errCopyInItself'      : 'امکان کپی کردن "$1" در درون خودش وجود ندارد.',
			'errRm'                : 'امکان حذف کردن "$1" وجود ندارد.',
			'errTrash'             : 'امکان حذف وجود ندارد.',
			'errRmSrc'             : 'امکان حذف فایل(ها) از مبدا وجود ندارد.',
			'errExtract'           : 'امکان استخراج فایل فشرده "$1" وجود ندارد.',
			'errArchive'           : 'امکان ایجاد فایل فشرده وجود ندارد.',
			'errArcType'           : 'نوع ناشناخته برای فایل فشرده.',
			'errNoArchive'         : 'این فایل فشرده نیست یا اینکه این نوع فایل فشرده پشتیبانی نمی شود.',
			'errCmdNoSupport'      : 'Backend از این دستور پشتیبانی نمی کند.',
			'errReplByChild'       : 'امکان جایگزینی پوشه "$1" با یک آیتم از درون خودش وجود ندارد.',
			'errArcSymlinks'       : 'به دلایل مسائل امنیتی امکان باز کردن فایل فشرده دارای symlinks وجود ندارد.',
			'errArcMaxSize'        : 'فایل های فشرده به حداکثر اندازه تعیین شده رسیده اند.',
			'errResize'            : 'امکان تغییر اندازه "$1" وجود ندارد.',
			'errResizeDegree'      : 'درجه چرخش نامعتبر است.',
			'errResizeRotate'      : 'امکان چرخش تصویر وجود ندارد.',
			'errResizeSize'        : 'اندازه تصویر نامعتبر است.',
			'errResizeNoChange'    : 'تغییری در اندازه تصویر ایجاد نشده است.',
			'errUsupportType'      : 'این نوع فایل پشتیبانی نمی شود.',
			'errNotUTF8Content'    : 'فایل "$1" به صورت UTF-8 ذخیره نشده و امکان ویرایش آن وجود ندارد.',
			'errNetMount'          : 'امکان اتصال "$1" وجود ندارد.',
			'errNetMountNoDriver'  : 'این پروتکل پشتیبانی نمی شود.',
			'errNetMountFailed'    : 'اتصال ناموفق بود.',
			'errNetMountHostReq'   : 'میزبان موردنیاز است.',
			'errSessionExpires'    : 'اعتبار جلسه کاری شما بدلیل عدم فعالیت برای مدت زمان طولانی به اتمام رسیده است.',
			'errCreatingTempDir'   : 'امکان ایجاد دایرکتوری موقت وجود ندارد: "$1"',
			'errFtpDownloadFile'   : 'امکان دریافت فایل از FTP وجود ندارد: "$1"',
			'errFtpUploadFile'     : 'امکان آپلود فایل به FTP وجود ندارد: "$1"',
			'errFtpMkdir'          : 'امکان ایجاد دایرکتوری برروی FTP وجود ندارد: "$1"',
			'errArchiveExec'       : 'خطا در زمان فشرده سازی این فایل‌ها: "$1"',
			'errExtractExec'       : 'خطا در زمان بازگشایی این فایل‌ها: "$1"',
			'errNetUnMount'        : 'امکان قطع اتصال وجود ندارد.',
			'errConvUTF8'          : 'امکان تبدیل به UTF-8 وجود ندارد',
			'errFolderUpload'      : 'جهت آپلود کردن پوشه، از یک مرورگر مدرن استفاده نمایید.',
			'errSearchTimeout'     : 'در هنگان جستجو برای "$1" خطایی رخ داده است. نتیجه جستجو به صورت ناتمام می باشد.',
			'errReauthRequire'     : 'اعتبارسنجی مجدد موردنیاز است.',
			'errMaxTargets'        : 'حداکثر تعداد انتخاب قابل قبول $1 می‌باشد.',
			'errRestore'           : 'امکان بازیابی وجود ندارد. مقصد بازیابی نامشخص است.',
			'errEditorNotFound'    : 'ویرایشگری برای این نوع فایل یافت نشد.',
			'errServerError'       : 'خطایی در سمت سرور به وجود آمده است.',
			'errEmpty'             : 'امکان خالی کردن پوشه "$1" وجود ندارد.',

			/******************************* commands names ********************************/
			'cmdarchive'   : 'ایجاد فایل فشرده',
			'cmdback'      : 'بازگشت به عقب',
			'cmdcopy'      : 'کپی',
			'cmdcut'       : 'بریدن',
			'cmddownload'  : 'دانلود',
			'cmdduplicate' : 'تکثیر فایل',
			'cmdedit'      : 'ویرایش محتوای فایل',
			'cmdextract'   : 'بازگشایی فایل فشرده',
			'cmdforward'   : 'حرکت به جلو',
			'cmdgetfile'   : 'انتخاب فایل‌ها',
			'cmdhelp'      : 'درباره این نرم‌افزار',
			'cmdhome'      : 'ریشه',
			'cmdinfo'      : 'مشاهده مشخصات',
			'cmdmkdir'     : 'پوشه جدید',
			'cmdmkdirin'   : 'انتقال به پوشه جدید',
			'cmdmkfile'    : 'فایل جدید',
			'cmdopen'      : 'باز کردن',
			'cmdpaste'     : 'چسباندن',
			'cmdquicklook' : 'پیش نمایش',
			'cmdreload'    : 'بارگذاری مجدد',
			'cmdrename'    : 'تغییر نام',
			'cmdrm'        : 'حذف',
			'cmdtrash'     : 'انتقال به سطل بازیافت',
			'cmdrestore'   : 'بازیابی',
			'cmdsearch'    : 'جستجوی فایل',
			'cmdup'        : 'رفتن به سطح بالاتر',
			'cmdupload'    : 'آپلود فایل',
			'cmdview'      : 'مشاهده',
			'cmdresize'    : 'تغییر اندازه و چرخش',
			'cmdsort'      : 'مرتب سازی',
			'cmdnetmount'  : 'اتصال درایو شبکه',
			'cmdnetunmount': 'قطع اتصال',
			'cmdplaces'    : 'به مسیرهای',
			'cmdchmod'     : 'تغییر حالت',
			'cmdopendir'   : 'بازکردن یک پوشه',
			'cmdcolwidth'  : 'بازنشانی عرض ستون',
			'cmdfullscreen': 'حالت نمایش تمام صفحه',
			'cmdmove'      : 'انتقال',
			'cmdempty'     : 'خالی کردن پوشه',
			'cmdundo'      : 'خنثی‌سازی',
			'cmdredo'      : 'انجام مجدد',
			'cmdpreference': 'تنظیمات',
			'cmdselectall' : 'انتخاب همه موارد',
			'cmdselectnone': 'لغو انتخاب',
			'cmdselectinvert': 'انتخاب معکوس',
			'cmdopennew'   : 'باز کردن در پنجره جدید',
			'cmdhide':'پنهان کردن (اولویت)',

			/*********************************** buttons ***********************************/
			'btnClose'  : 'بستن',
			'btnSave'   : 'ذخیره',
			'btnRm'     : 'حذف',
			'btnApply'  : 'اعمال',
			'btnCancel' : 'انصراف',
			'btnNo'     : 'خیر',
			'btnYes'    : 'بلی',
			'btnMount'  : 'اتصال',
			'btnApprove': 'رفتن به $1 و تایید',
			'btnUnmount': 'قطع اتصال',
			'btnConv'   : 'تبدیل',
			'btnCwd'    : 'اینجا',
			'btnVolume' : 'درایو',
			'btnAll'    : 'همه',
			'btnMime'   : 'نوع فایل',
			'btnFileName':'نام فایل',
			'btnSaveClose': 'ذخیره و بستن',
			'btnBackup' : 'پشتیبان‌گیری',
			'btnRename'    : 'تغییر نام',
			'btnRenameAll' : 'تغییر نام(همه)',
			'btnPrevious' : 'قبلی ($1/$2)',
			'btnNext'     : 'بعدی ($1/$2)',
			'btnSaveAs'   : 'ذخیره با نام جدید',

			/******************************** notifications ********************************/
			'ntfopen'     : 'در حال باز کردن پوشه',
			'ntffile'     : 'در حال باز کردن فایل',
			'ntfreload'   : 'بارگذاری مجدد محتویات پوشه',
			'ntfmkdir'    : 'در حال ایجاد پوشه',
			'ntfmkfile'   : 'در حال ایجاد فایل',
			'ntfrm'       : 'در حال حذف موارد موردنظر',
			'ntfcopy'     : 'در حال کپی موارد موردنظر',
			'ntfmove'     : 'در حال انتقال موارد موردنظر',
			'ntfprepare'  : 'بررسی موارد موجود',
			'ntfrename'   : 'در حال تغییر نام فایل',
			'ntfupload'   : 'در حال آپلود فایل',
			'ntfdownload' : 'در حال دانلود فایل',
			'ntfsave'     : 'در حال ذخیره فایل',
			'ntfarchive'  : 'در حال ایجاد فایل فشرده',
			'ntfextract'  : 'در حال استخراج فایل ها از حالت فشرده',
			'ntfsearch'   : 'در حال جستجوی فایل',
			'ntfresize'   : 'در حال تغییر اندازه تصاویر',
			'ntfsmth'     : 'درحال انجام عملیات ....',
			'ntfloadimg'  : 'در حال بارگذاری تصویر',
			'ntfnetmount' : 'در حال اتصال درایو شبکه',
			'ntfnetunmount': 'قطع اتصال درایو شبکه',
			'ntfdim'      : 'در حال محاسبه ابعاد تصویر',
			'ntfreaddir'  : 'در حال دریافت مشخصات پوشه',
			'ntfurl'      : 'در حال دریافت URL',
			'ntfchmod'    : 'در حال تغییر نوع فایل',
			'ntfpreupload': 'در حال تایید نام فایل جهت آپلود',
			'ntfzipdl'    : 'در حال ایجاد فایل جهت دانلود',
			'ntfparents'  : 'در حال دریافت اطلاعات مسیر',
			'ntfchunkmerge': 'در حال پردازش فایل آپلود شده',
			'ntftrash'    : 'در حال انتقال به سطل بازیافت',
			'ntfrestore'  : 'در حال بازیابی از سطل بازیافت',
			'ntfchkdir'   : 'بررسی پوشه مقصد',
			'ntfundo'     : 'در حال خنثی‌سازی آخرین عملیات',
			'ntfredo'     : 'در حال انجام مجدد آخرین عملیات',

			/*********************************** volumes *********************************/
			'volume_Trash' : 'سطل بازیافت',

            /************************************ dates **********************************/
			'dateUnknown' : 'نامعلوم',
			'Today'       : 'امروز',
			'Yesterday'   : 'دیروز',
			'msJan'       : 'ژانویه',
			'msFeb'       : 'فوریه',
			'msMar'       : 'مارس',
			'msApr'       : 'آوریل',
			'msMay'       : 'می',
			'msJun'       : 'جون',
			'msJul'       : 'جولای',
			'msAug'       : 'آگوست',
			'msSep'       : 'سپتامبر',
			'msOct'       : 'اکتبر',
			'msNov'       : 'نوامبر',
			'msDec'       : 'دسامبر',
			'January'     : 'ژانویه',
			'February'    : 'فوریه',
			'March'       : 'مارس',
			'April'       : 'آوریل',
			'May'         : 'می',
			'June'        : 'جون',
			'July'        : 'جولای',
			'August'      : 'آگوست',
			'September'   : 'سپتامبر',
			'October'     : 'اکتبر',
			'November'    : 'نوامبر',
			'December'    : 'دسامبر',
			'Sunday'      : 'یک‌شنبه',
			'Monday'      : 'دوشنبه',
			'Tuesday'     : 'سه‌شنبه',
			'Wednesday'   : 'چهارشنبه',
			'Thursday'    : 'پنج‌شنبه',
			'Friday'      : 'جمعه',
			'Saturday'    : 'شنبه',
			'Sun'         : 'یک‌شنبه',
			'Mon'         : 'دوشنبه',
			'Tue'         : 'سه‌شنبه',
			'Wed'         : 'چهارشنبه',
			'Thu'         : 'پنج‌شنبه',
			'Fri'         : 'جمعه',
			'Sat'         : 'شنبه',

			/******************************** sort variants ********************************/
			'sortname'          : 'بر اساس نام',
			'sortkind'          : 'بر اساس نوع',
			'sortsize'          : 'بر اساس اندازه',
			'sortdate'          : 'بر اساس تاریخ',
			'sortFoldersFirst'  : 'پوشه‌ها در ابتدای لیست',
			'sortperm'          : 'براساس سطح دسترسی',
			'sortmode'          : 'براساس مد دسترسی',
			'sortowner'         : 'براساس مالک',
			'sortgroup'         : 'براساس گروه',
			'sortAlsoTreeview'  : 'همچنین نمای درختی',

			/********************************** new items **********************************/
			'untitled file.txt' : 'NewFile.txt',
			'untitled folder'   : 'NewFolder',
			'Archive'           : 'NewArchive',

			/********************************** messages **********************************/
			'confirmReq'      : 'تایید نهایی عملیات ضروری است.',
			'confirmRm'       : 'آیا مطمئنید که موارد انتخابی حذف شوند؟ موارد حدف شده قابل بازیابی نخواهند بود!',
			'confirmRepl'     : 'مالیلد جایگزینی فایل قدیمی با فایل جدید انجام شود؟ (برای جایگزینی پوشه محتوای قدیمی با محتوای پوشه جدید ادغام خواهد شد. برای تهیه پشتیبانی و سپس جایگزینی گزینه پشتیبان‌گیری را انتخاب نمایید)',
			'confirmRest'     : 'آیا مایلید موارد موجود با موارد بازیابی شده از سطل بازیافت جایگزین شود؟',
			'confirmConvUTF8' : 'UTF-8 نیست<br/>تبدیل به UTF-8 انجام شود؟<br/>پس از ذخیره سازی محتوا به صورت UTF-8 خواهد بود.',
			'confirmNonUTF8'  : 'encoding این فایل قابل تشخیص نیست. جهت ویرایش نیاز است که به صورت موقت به UTF-8 تبدیل شود.<br/>لطفا encoding فایل را انتخاب نمایید.',
			'confirmNotSave'  : 'تغییراتی اعمال شده است.<br/>در صورت عدم ذخیره تغییرات از بین خواهد رفت.',
			'confirmTrash'    : 'آیا مطمئنید که این موارد به سطل بازیافت منتقل شوند؟',
			'apllyAll'        : 'اعمال تغییرات به همه موارد',
			'name'            : 'نام',
			'size'            : 'اندازه',
			'perms'           : 'سطح دسترسی',
			'modify'          : 'آخرین تغییرات',
			'kind'            : 'نوع',
			'read'            : 'خواندن',
			'write'           : 'نوشتن',
			'noaccess'        : 'دسترسی وجود ندارد',
			'and'             : 'و',
			'unknown'         : 'نامعلوم',
			'selectall'       : 'انتخاب همه موارد',
			'selectfiles'     : 'انتخاب یک یا چند مورد',
			'selectffile'     : 'انتخاب اولین مورد',
			'selectlfile'     : 'انتخاب آخرین مورد',
			'viewlist'        : 'حالت نمایش لیست',
			'viewicons'       : 'نمایش با آیکون',
			'viewSmall'       : 'آیکون‌های کوچک',
			'viewMedium'      : 'آیکون‌های متوسط',
			'viewLarge'       : 'آیکون‌های بزرگ',
			'viewExtraLarge'  : 'آیکون‌های خیلی بزرگ',
			'places'          : 'مسیرها',
			'calc'            : 'محاسبه',
			'path'            : 'مسیر',
			'aliasfor'        : 'نام مستعار برای',
			'locked'          : 'قفل شده',
			'dim'             : 'ابعاد',
			'files'           : 'فایل‌ها',
			'folders'         : 'پوشه‌ها',
			'items'           : 'آیتم‌ها',
			'yes'             : 'بلی',
			'no'              : 'خیر',
			'link'            : 'لینک',
			'searcresult'     : 'نتایج جستجو',
			'selected'        : 'موارد انتخاب شده',
			'about'           : 'درباره',
			'shortcuts'       : 'میانبرها',
			'help'            : 'راهنمایی',
			'webfm'           : 'مدیر فایل تحت وب',
			'ver'             : 'نسخه',
			'protocolver'     : 'نسخه پروتکل',
			'homepage'        : 'صفحه اصلی پروژه',
			'docs'            : 'مستندات',
			'github'          : 'صفحه پروژه را در Github مشاهده کنید',
			'twitter'         : 'ما را در Twitter دنبال کنید',
			'facebook'        : 'به ما در facebook ملحق شوید',
			'team'            : 'تیم',
			'chiefdev'        : 'توسعه دهنده اصلی',
			'developer'       : 'توسعه دهنده',
			'contributor'     : 'مشارکت کننده',
			'maintainer'      : 'پشتیبان',
			'translator'      : 'مترجم',
			'icons'           : 'آیکون‌ها',
			'dontforget'      : 'and don\'t forget to take your towel',
			'shortcutsof'     : 'میانبرها غیرفعال شده‌اند.',
			'dropFiles'       : 'فایل ها در این بخش رها کنید.',
			'or'              : 'یا',
			'selectForUpload' : 'انتخاب فایل جهت آپلود',
			'moveFiles'       : 'انتقال موارد',
			'copyFiles'       : 'کپی موارد',
			'restoreFiles'    : 'بازیابی موارد',
			'rmFromPlaces'    : 'حذف',
			'aspectRatio'     : 'نسبت تصویر',
			'scale'           : 'مقیاس',
			'width'           : 'طول',
			'height'          : 'ارتفاع',
			'resize'          : 'تغییر اندازه',
			'crop'            : 'بریدن',
			'rotate'          : 'چرخاندن',
			'rotate-cw'       : 'چرخاندن 90 درجه در جهت عقربه‌های ساعت',
			'rotate-ccw'      : 'چرخاندن 90 درجه در جهت خلاف عقربه‌های ساعت',
			'degree'          : '°',
			'netMountDialogTitle' : 'اتصال درایو شبکه',
			'protocol'        : 'پروتکل',
			'host'            : 'میزبان',
			'port'            : 'پورت',
			'user'            : 'نام کاربری',
			'pass'            : 'کلمه عبور',
			'confirmUnmount'      : 'مطمئن به قطع اتصال $1 می باشد؟',
			'dropFilesBrowser': 'فایل‌ها را به داخل این کادر بیندازید یا از حافظه paste کنید',
			'dropPasteFiles'  : 'فایل‌ها را به داخل این کادر بیندازید یا از داخل حافظه آدرس URL/تصاویر را paste کنید',
			'encoding'        : 'نوع Encoding',
			'locale'          : 'نوع Locale',
			'searchTarget'    : 'مقصد: $1',
			'searchMime'      : 'جستجو براساس MIME Type وارد شده',
			'owner'           : 'مالک',
			'group'           : 'گروه',
			'other'           : 'سایر',
			'execute'         : 'قابل اجرا',
			'perm'            : 'سطح دسترسی',
			'mode'            : 'مد دسترسی',
			'emptyFolder'     : 'پوشه خالی است',
			'emptyFolderDrop' : 'پوشه خالی است، فایل‌ها را جهت افزودن کشیده و رها کنید',
			'emptyFolderLTap' : 'پوشه خالی است، یک اشاره طولانی برای افزودن فایل کافی است',
			'quality'         : 'کیفیت',
			'autoSync'        : 'همگام‌سازی خودکار',
			'moveUp'          : 'حرکت به بالا',
			'getLink'         : 'دریافت URL لینک',
			'share'           : 'اشتراک گذاری',
			'selectedItems'   : 'موارد انتخاب شده ($1)',
			'folderId'        : 'شناسه پوشه',
			'offlineAccess'   : 'اجازه دسترسی به صورت آفلاین',
			'reAuth'          : 'جهت اعتبارسنجی مجدد',
			'nowLoading'      : 'در حال بازگذاری...',
			'openMulti'       : 'بازکردن چندین فایل',
			'openMultiConfirm': 'شما قصد باز کردن $1 فایل را دارید. آیا مایلید همه موارد در مرورگر باز شود؟',
			'emptySearch'     : 'موردی یافت نشد.',
			'editingFile'     : 'در حال ویرایش یک فایل.',
			'hasSelected'     : 'شما $1 مورد را انتخاب کرده‌اید.',
			'hasClipboard'    : 'در حافظه $1 مورد وجود دارد.',
			'incSearchOnly'   : 'جستجوی افزایش فقط از نمای فعلی.',
			'reinstate'       : 'بازگرداندن',
			'complete'        : 'عملیات $1 انجام شد',
			'contextmenu'     : 'منو راست',
			'pageTurning'     : 'چرخش صفحه',
			'volumeRoots'     : 'ریشه‌های درایو',
			'reset'           : 'بازنشانی',
			'bgcolor'         : 'رنگ پس زمینه',
			'colorPicker'     : 'انتخابگر رنگ',
			'8pxgrid'         : 'گرید 8px',
			'enabled'         : 'فعال شده',
			'disabled'        : 'غیرفعال شده',
			'emptyIncSearch'  : 'در نمای فعلی موردی یافت نشد.\\Aبا فشردن کلید Enter مسیر جستجو را تغییر دهید.',
			'emptyLetSearch'  : 'برای جستجوی تک حرفی در نمایش فعلی موردی یافت نشد.',
			'textLabel'       : 'عنوان متنی',
			'minsLeft'        : '$1 دقیقه باقیمانده',
			'openAsEncoding'  : 'باز کردن مجدد با encoding انتخاب شده',
			'saveAsEncoding'  : 'ذخیره با encoding انتخاب شده',
			'selectFolder'    : 'انتخاب پوشه',
			'firstLetterSearch': 'جستجوی تک حرفی',
			'presets'         : 'از پیش تعیین شده',
			'tooManyToTrash'  : 'موارد زیاد است و امکان انتقال به سطل بازیافت وجود ندارد.',
			'TextArea'        : 'ویرایش محتوا',
			'folderToEmpty'   : 'خالی کردن پوشه "$1".',
			'filderIsEmpty'   : 'پوشه "$1" ‌ذاتا خالی است.',
			'preference'      : 'تنظیمات',
			'language'        : 'زبان',
			'clearBrowserData': 'بازبینی تنظیمات ذخیره شده در این مرورگر',
			'toolbarPref'     : 'تنظیمات نوار ابزار',
			'charsLeft'       : '... $1 کاراکتر باقیمانده.',
			'sum'             : 'مجموع',
			'roughFileSize'   : 'اندازه فایل نامتعارف',
			'autoFocusDialog' : 'انتخاب عناصر داخل دیالوگ با mouseover',
			'select'          : 'انتخاب',
			'selectAction'    : 'عملیات به هنگام انتخاب فایل',
			'useStoredEditor' : 'باز کردن با ویرایشگر مورداستفاده در آخرین دفعه',
			'selectinvert'    : 'انتخاب معکوس',
			'renameMultiple'  : 'آیا مایل به تغییر نام $1 مورد انتخاب شده همانند $2 هستید؟<br/>امکان بازگرداندن این تغییر پس از اعمالو جود ندارد!',
			'batchRename'     : 'تغییرنام گروهی',
			'plusNumber'      : '+ عدد',
			'asPrefix'        : 'افزودن پیشوند',
			'asSuffix'        : 'افزودن پسوند',
			'changeExtention' : 'تغییر پسوند فایل',
			'columnPref'      : 'تنظیمات ستون‌ها (حالت نمایش لیست)',
			'reflectOnImmediate' : 'تمامی تغییرات به صورت آنی برروی فایل فشرده اعمال خواهد شد.',
			'reflectOnUnmount'   : 'تمامی تغییرات تا زمانی که اتصال این درایو قطع نشده است اعمال نخواهند شد.',
			'unmountChildren' : 'اتصال به درایوهای زیر قطع خواهد شد. آیا مطمئن به ادامه عملیات هستید؟',
			'selectionInfo'   : 'مشخصات',
			'hashChecker'     : 'الگوریتم های نمایش hash فایل',
			'infoItems'       : 'موارد اطلاعات',
			'pressAgainToExit': 'جهت خروج مجدد فشار دهید.',
			'toolbar'         : 'نوار ابزار',
			'workspace'       : 'فضای کاری',
			'dialog'          : 'پنجره دیالوگ',
			'all'             : 'همه',
			'iconSize'        : 'اندازه آیکون‌ها (نمایش به صورت آیکون)',
			'editorMaximized' : 'باز کردن پنجره ویرایشگر به صورت تمام صفحه',
			'editorConvNoApi' : 'بدلیل در دسترسی نبودن تبدیل از طریق API، لطفا برروی وب سایت تبدیل را انجام دهید.',
			'editorConvNeedUpload' : 'پس از تبدیل, شما بایستی از طریق آدرس URL یا فایل دریافت شده آپلود را انجاد دهید تا فایل تبدیل شده ذخیره گردد.',
			'convertOn'       : 'تبدیل برروی سایت از $1',
			'integrations'    : 'هماهنگ سازی‌ها',
			'integrationWith' : 'elFinder با سرویس های زیر هماهنگ شده است. لطفا ابتدا شرایط استفاده، مقررات حریم خصوصی و سایر موارد را مطالعه بفرمایید.',
			'Code Editor':'ویرایشگر کد',
            'extentiontype' : 'نوع پسوند',
            /********************************** mimetypes **********************************/
			'kindUnknown'     : 'نامعلوم',
			'kindRoot'        : 'ریشه درایو',
			'kindFolder'      : 'پوشه',
			'kindSelects'     : 'انتخاب شده‌ها',
			'kindAlias'       : 'اسم مستعار',
			'kindAliasBroken' : 'اسم مستعار ناقص',
			// applications
			'kindApp'         : 'برنامه',
			'kindPostscript'  : 'سند Postscript',
			'kindMsOffice'    : 'سند Microsoft Office',
			'kindMsWord'      : 'سند Microsoft Word',
			'kindMsExcel'     : 'سند Microsoft Excel',
			'kindMsPP'        : 'فایل ارایه Microsoft Powerpoint',
			'kindOO'          : 'سند Open Office',
			'kindAppFlash'    : 'برنامه فلش',
			'kindPDF'         : 'سند قابل حمل (PDF)',
			'kindTorrent'     : 'فایل تورنت',
			'kind7z'          : 'فایل فشرده 7z',
			'kindTAR'         : 'فایل فشرده TAR',
			'kindGZIP'        : 'فایل فشرده GZIP',
			'kindBZIP'        : 'فایل فشرده BZIP',
			'kindXZ'          : 'فایل فشرده XZ',
			'kindZIP'         : 'فایل فشرده ZIP',
			'kindRAR'         : 'فایل فشرده RAR',
			'kindJAR'         : 'فایل JAR مربوط به جاوا',
			'kindTTF'         : 'فونت True Type',
			'kindOTF'         : 'فونت Open Type',
			'kindRPM'         : 'بسته RPM',
			// texts
			'kindText'        : 'سند متنی',
			'kindTextPlain'   : 'سند متنی ساده',
			'kindPHP'         : 'سورس کد PHP',
			'kindCSS'         : 'فایل style sheet',
			'kindHTML'        : 'سند HTML',
			'kindJS'          : 'سورس کد Javascript',
			'kindRTF'         : 'سند متنی غنی',
			'kindC'           : 'سورس کد C',
			'kindCHeader'     : 'سورس کد C header',
			'kindCPP'         : 'سورس کد C++',
			'kindCPPHeader'   : 'سورس کد C++ header',
			'kindShell'       : 'اسکریپت شل یونیکس',
			'kindPython'      : 'سورس کد Python',
			'kindJava'        : 'سورس کد Java',
			'kindRuby'        : 'سورس کد Ruby',
			'kindPerl'        : 'اسکریپت Perl',
			'kindSQL'         : 'سورس کد SQL',
			'kindXML'         : 'سند XML',
			'kindAWK'         : 'سورس کد AWK',
			'kindCSV'         : 'مقادیر جداشده با کامل',
			'kindDOCBOOK'     : 'سند Docbook XML',
			'kindMarkdown'    : 'سند متنی Markdown',
			// images
			'kindImage'       : 'تصویر',
			'kindBMP'         : 'تصویر BMP',
			'kindJPEG'        : 'تصویر JPEG',
			'kindGIF'         : 'تصویر GIF',
			'kindPNG'         : 'تصویر PNG',
			'kindTIFF'        : 'تصویر TIFF',
			'kindTGA'         : 'تصویر TGA',
			'kindPSD'         : 'تصویر Adobe Photoshop',
			'kindXBITMAP'     : 'تصویر X bitmap',
			'kindPXM'         : 'تصویر Pixelmator',
			// media
			'kindAudio'       : 'فایل صوتی',
			'kindAudioMPEG'   : 'فایل صوتی MPEG',
			'kindAudioMPEG4'  : 'فایل صوتی MPEG-4',
			'kindAudioMIDI'   : 'فایل صوتی MIDI',
			'kindAudioOGG'    : 'فایل صوتی Ogg Vorbis',
			'kindAudioWAV'    : 'فایل صوتی WAV',
			'AudioPlaylist'   : 'لیست پخش MP3',
			'kindVideo'       : 'فایل ویدیویی',
			'kindVideoDV'     : 'فایل ویدیویی DV',
			'kindVideoMPEG'   : 'فایل ویدیویی MPEG',
			'kindVideoMPEG4'  : 'فایل ویدیویی MPEG-4',
			'kindVideoAVI'    : 'فایل ویدیویی AVI',
			'kindVideoMOV'    : 'فایل ویدیویی Quick Time',
			'kindVideoWM'     : 'فایل ویدیویی Windows Media',
			'kindVideoFlash'  : 'فایل ویدیویی Flash',
			'kindVideoMKV'    : 'فایل ویدیویی Matroska',
			'kindVideoOGG'    : 'فایل ویدیویی Ogg'
		}
	};
}));