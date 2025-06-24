var Main = {

    //+++++++++++++++++++++++ مدیریت کاربران ++++++++++++++++++++++++++++
    checkPassword : function (oldPass){
        var action = "checkPassword";
        var param = {action:action,oldPass:oldPass};
        return this.mainAjaxRequest(param);
    },
    getUserInfo : function (uid){
        var action = "getUserInfo";
        var param = {action:action,uid:uid};
        return this.mainAjaxRequest(param);
    },
    getUserAllAccessHtm : function(uid){
        var action = "getUserAllAccessHtm";
        var param = {action:action,uid:uid};
        return this.mainAjaxRequest(param);
    },
    //+++++++++++++++++++++++ مدیریت قطعات ++++++++++++++++++++++++++++
    getPieceInfo : function (pid){
        var action = "getPieceInfo";
        var param = {action:action,pid:pid};
        return this.mainAjaxRequest(param);
    },
    getPieceNameList : function(){
        var action = "getPieceNameList";
        var param = {action:action};
        return this.mainAjaxRequest(param);
    },
    getFieldNameList : function(){
        var action = "getFieldNameList";
        var param = {action:action};
        return this.mainAjaxRequest(param);
    },
    //+++++++++++++++++++++++ مدیریت صنایع ++++++++++++++++++++++++++++
    getUnitEfficiency : function (UEid){
        var action = "getUnitEfficiency";
        var param = {action:action,UEid:UEid};
        return this.mainAjaxRequest(param);
    },
    getProductiveUnits : function (){
        var action = "getProductiveUnits";
        var param = {action:action};
        return this.mainAjaxRequest(param);
    },
    //+++++++++++++++++++++++ مدیریت بار برنج ++++++++++++++++++++++++++++
    getBrassWeight : function (){
        var action = "getBrassWeight";
        var param = {action:action};
        return this.mainAjaxRequest(param);
    },
    //+++++++++++++++++++++++ مدیریت محصولات ++++++++++++++++++++++++++++
    getGoodInfo : function (gid){
        var action = "getGoodInfo";
        var param = {action:action,gid:gid};
        return this.mainAjaxRequest(param);
    },
    getGoodNameList : function(){
        var action = "getGoodNameList";
        var param = {action:action};
        return this.mainAjaxRequest(param);
    },
    getGoodGroupList : function (brand){
        var action = "getGoodGroupList";
        var param = {action:action,brand:brand};
        return this.mainAjaxRequest(param);
    },
    getGoodSGroupList : function (group){
        var action = "getGoodSGroupList";
        var param = {action:action,group:group};
        return this.mainAjaxRequest(param);
    },
    getGoodSeriesList : function (sgroup){
        var action = "getGoodSeriesList";
        var param = {action:action,sgroup:sgroup};
        return this.mainAjaxRequest(param);
    },
    getGoodColorList : function (series){
        var action = "getGoodColorList";
        var param = {action:action,series:series};
        return this.mainAjaxRequest(param);
    },
    getFieldNamesList : function(){
        var action = "getFieldNamesList";
        var param = {action:action};
        return this.mainAjaxRequest(param);
    },
    //+++++++++++++++++++++++ مدیریت نرخ ارز ++++++++++++++++++++++++++++
    getCurrencyInfo : function (cid){
        var action = "getCurrencyInfo";
        var param = {action:action,cid:cid};
        return this.mainAjaxRequest(param);
    },
    checkExistDollar : function (){
        var action = "checkExistDollar";
        var param = {action:action};
        return this.mainAjaxRequest(param);
    },
    getDollarPrice : function (){
        var action = "getDollarPrice";
        var param = {action:action};
        return this.mainAjaxRequest(param);
    },
    Num_format : function (number){
        var action = "Num_format";
        var param = {action:action,number:number};
        return this.mainAjaxRequest(param);
    },
    //+++++++++++++++++++++++ مدیریت هزینه های پرسنل ++++++++++++++++++++++++++++
    getUnitInfo : function (uid){
        var action = "getUnitInfo";
        var param = {action:action,uid:uid};
        return this.mainAjaxRequest(param);
    },
    getPersonnelInfo : function (pid){
        var action = "getPersonnelInfo";
        var param = {action:action,pid:pid};
        return this.mainAjaxRequest(param);
    },
    getPersonnelDocInfo : function (pid){
        var action = "getPersonnelDocInfo";
        var param = {action:action,pid:pid};
        return this.mainAjaxRequest(param);
    },
    getAbilityList : function(gid){
        var action = "getAbilityList";
        var param = {action:action,gid:gid};
        return this.mainAjaxRequest(param);
    },
    getAbilityList1 : function(gid){
        var action = "getAbilityList1";
        var param = {action:action,gid:gid};
        return this.mainAjaxRequest(param);
    },
    getSalaryGroupInfo : function (){
        var action = "getSalaryGroupInfo";
        var param = {action:action};
        return this.mainAjaxRequest(param);
    },
    getPersonnelSalaryGroupList : function(sgid){
        var action = "getPersonnelSalaryGroupList";
        var param = {action:action,sgid:sgid};
        return this.mainAjaxRequest(param);
    },
    //+++++++++++++++++++++++ مدیریت روزهای در دسترس ++++++++++++++++++++++++++++
    getAvailableDayInfo : function (ADid){
        var action = "getAvailableDayInfo";
        var param = {action:action,ADid:ADid};
        return this.mainAjaxRequest(param);
    },
    //+++++++++++++++++++++++ درصد ضایعات و بهره ++++++++++++++++++++++++++++
    getPercentagesInfo : function (){
        var action = "getPercentagesInfo";
        var param = {action:action};
        return this.mainAjaxRequest(param);
    },
    //+++++++++++++++++++++++ درصد تخفیفات فروش ++++++++++++++++++++++++++++
    getPerDiscountInfo : function (brand,group){
        var action = "getPerDiscountInfo";
        var param = {action:action,brand:brand,group:group};
        return this.mainAjaxRequest(param);
    },
    getGoodSalePrice : function (gid){
        var action = "getGoodSalePrice";
        var param = {action:action,gid:gid};
        return this.mainAjaxRequest(param);
    },
    //+++++++++++++++++++++++ محاسبه قیمت در جریان ساخت ++++++++++++++++++++++++++++
    getRawMaterialCode : function (pid){
        var action = "getRawMaterialCode";
        var param = {action:action,pid:pid};
        return this.mainAjaxRequest(param);
    },
    //+++++++++++++++++++++++ قیمت فروش محصولات ++++++++++++++++++++++++++++
    getIncreaseChrome : function (){
        var action = "getIncreaseChrome";
        var param = {action:action};
        return this.mainAjaxRequest(param);
    },
    getGoodCalcInfo : function (gid){
        var action = "getGoodCalcInfo";
        var param = {action:action,gid:gid};
        return this.mainAjaxRequest(param);
    },
    //+++++++++++++++++++++++ اظهارنظر و درخواست پرداخت وجه ++++++++++++++++++++++++++++
    getAccountInfo : function (aid){
        var action = "getAccountInfo";
        var param = {action:action,aid:aid};
        return this.mainAjaxRequest(param);
    },
    getCommentInfo : function (cid){
        var action = "getCommentInfo";
        var param = {action:action,cid:cid};
        return this.mainAjaxRequest(param);
    },
    getAccountNameList : function (){
        var action = "getAccountNameList";
        var param = {action:action};
        return this.mainAjaxRequest(param);
    },
    getTypeNameList : function (){
        var action = "getTypeNameList";
        var param = {action:action};
        return this.mainAjaxRequest(param);
    },
    getAccountNumList : function (cfor){
        var action = "getAccountNumList";
        var param = {action:action,cfor:cfor};
        return this.mainAjaxRequest(param);
    },
    getAccountNumListWithName : function (cfor){
        var action = "getAccountNumListWithName";
        var param = {action:action,cfor:cfor};
        return this.mainAjaxRequest(param);
    },
    getCommentUnitInfo : function (uid){
        var action = "getCommentUnitInfo";
        var param = {action:action,uid:uid};
        return this.mainAjaxRequest(param);
    },
    getCommentTypeID : function (type){
        var action = "getCommentTypeID";
        var param = {action:action,type:type};
        return this.mainAjaxRequest(param);
    },
    getDepositorInfo : function (did){
        var action = "getDepositorInfo";
        var param = {action:action,did:did};
        return this.mainAjaxRequest(param);
    },
    getDepositorNameList : function (){
        var action = "getDepositorNameList";
        var param = {action:action};
        return this.mainAjaxRequest(param);
    },
    getBankNameList : function (){
        var action = "getBankNameList";
        var param = {action:action};
        return this.mainAjaxRequest(param);
    },
    getCatComment : function (pid){
        var action = "getCatComment";
        var param = {action:action,pid:pid};
        return this.mainAjaxRequest(param);
    },
    getCommentCheckInfo : function (cid){
        var action = "getCommentCheckInfo";
        var param = {action:action,cid:cid};
        return this.mainAjaxRequest(param);
    },
    getCommentDepositInfo : function (pid,deleteShow,payOrReport,sendMali,confMali){
        var action = "getCommentDepositInfo";
        var param = {action:action,pid:pid,deleteShow:deleteShow,payOrReport:payOrReport,sendMali:sendMali,confMali:confMali};
        return this.mainAjaxRequest(param);
    },
    getDepositorCode : function (depositor){
        var action = "getDepositorCode";
        var param = {action:action,depositor:depositor};
        return this.mainAjaxRequest(param);
    },
    getDepositorNameWC : function (code){
        var action = "getDepositorNameWC";
        var param = {action:action,code:code};
        return this.mainAjaxRequest(param);
    },
    getSubLayers : function(layer1){
        var action = "getSubLayers";
        var param = {action:action,layer1:layer1};
        return this.mainAjaxRequest(param);
    },
    getAttachedCommentFile : function (cid){
        var action = "getAttachedCommentFile";
        var param = {action:action,cid:cid};
        return this.mainAjaxRequest(param);
    },
    getAttachedContractFile : function (cid){
        var action = "getAttachedContractFile";
        var param = {action:action,cid:cid};
        return this.mainAjaxRequest(param);
    },
    getContractCommentDates : function (cid){
        var action = "getContractCommentDates";
        var param = {action:action,cid:cid};
        return this.mainAjaxRequest(param);
    },
    getContractInfo : function (cid){
        var action = "getContractInfo";
        var param = {action:action,cid:cid};
        return this.mainAjaxRequest(param);
    },
    getContractType : function (cid){
        var action = "getContractType";
        var param = {action:action,cid:cid};
        return this.mainAjaxRequest(param);
    },
    getReceivedCustomerInfo : function (reid){
        var action = "getReceivedCustomerInfo";
        var param = {action:action,reid:reid};
        return this.mainAjaxRequest(param);
    },
    getAttachedReceivedCustomerFile : function (reid){
        var action = "getAttachedReceivedCustomerFile";
        var param = {action:action,reid:reid};
        return this.mainAjaxRequest(param);
    },
    getFundDetailsComment : function (cid){
        var action = "getFundDetailsComment";
        var param = {action:action,cid:cid};
        return this.mainAjaxRequest(param);
    },
    getFundListInfo : function (fid){
        var action = "getFundListInfo";
        var param = {action:action,fid:fid};
        return this.mainAjaxRequest(param);
    },
    getFundListDetailsInfo : function (fid){
        var action = "getFundListDetailsInfo";
        var param = {action:action,fid:fid};
        return this.mainAjaxRequest(param);
    },
    getFundListAttachInfo : function (fdid){
        var action = "getFundListAttachInfo";
        var param = {action:action,fdid:fdid};
        return this.mainAjaxRequest(param);
    },
    getFundListDetailsShow : function (fid){
        var action = "getFundListDetailsShow";
        var param = {action:action,fid:fid};
        return this.mainAjaxRequest(param);
    },
    getFundListAttachShow : function (fdid){
        var action = "getFundListAttachShow";
        var param = {action:action,fdid:fdid};
        return this.mainAjaxRequest(param);
    },
    getFundListDetailsReport : function (fid){
        var action = "getFundListDetailsReport";
        var param = {action:action,fid:fid};
        return this.mainAjaxRequest(param);
    },
    getFundListAttachReport : function (fdid){
        var action = "getFundListAttachReport";
        var param = {action:action,fdid:fdid};
        return this.mainAjaxRequest(param);
    },
    getCardboard : function(){
        var action = "getCardboard";
        var param = {action:action};
        return this.mainAjaxRequest(param);
    },
    getCardboard1 : function(){
        var action = "getCardboard1";
        var param = {action:action};
        return this.mainAjaxRequest(param);
    },
    getFundListDetailsDeletedComment : function (fid){
        var action = "getFundListDetailsDeletedComment";
        var param = {action:action,fid:fid};
        return this.mainAjaxRequest(param);
    },
    getTempSendCommentInfo : function (pwID){
        var action = "getTempSendCommentInfo";
        var param = {action:action,pwID:pwID};
        return this.mainAjaxRequest(param);
    },
    //+++++++++++++++++++++++ مستندات سازمانی ++++++++++++++++++++++++++++
    getRegulationsInfo : function (rid){
        var action = "getRegulationsInfo";
        var param = {action:action,rid:rid};
        return this.mainAjaxRequest(param);
    },
    getAttachedRegulationsFile : function (rid){
        var action = "getAttachedRegulationsFile";
        var param = {action:action,rid:rid};
        return this.mainAjaxRequest(param);
    },
    getCircularsInfo : function (cid){
        var action = "getCircularsInfo";
        var param = {action:action,cid:cid};
        return this.mainAjaxRequest(param);
    },
    getAttachedCircularsFile : function (cid){
        var action = "getAttachedCircularsFile";
        var param = {action:action,cid:cid};
        return this.mainAjaxRequest(param);
    },
    getLegalContractInfo : function (lcid){
        var action = "getLegalContractInfo";
        var param = {action:action,lcid:lcid};
        return this.mainAjaxRequest(param);
    },
    getAttachedLegalContractFile : function (lcid){
        var action = "getAttachedLegalContractFile";
        var param = {action:action,lcid:lcid};
        return this.mainAjaxRequest(param);
    },
    //+++++++++++++++++++++++ شناسنامه ماشین ها ++++++++++++++++++++++++++++
    getCarInfo : function (caid){
        var action = "getCarInfo";
        var param = {action:action,caid:caid};
        return this.mainAjaxRequest(param);
    },
    getCarFundListDetailsShow : function (fid){
        var action = "getCarFundListDetailsShow";
        var param = {action:action,fid:fid};
        return this.mainAjaxRequest(param);
    },
    getCarThreeLayers : function (carLayer){
        var action = "getCarThreeLayers";
        var param = {action:action,carLayer:carLayer};
        return this.mainAjaxRequest(param);
    },
    getEnterExitCarList : function (caid,sDate,eDate,dName,eeType){
        var action = "getEnterExitCarList";
        var param = {action:action,caid:caid,sDate:sDate,eDate:eDate,dName:dName,eeType:eeType};
        return this.mainAjaxRequest(param);
    },
    getConsumingMaterialsList : function (caid){
        var action = "getConsumingMaterialsList";
        var param = {action:action,caid:caid};
        return this.mainAjaxRequest(param);
    },
    getExtraEquipment : function (cid){
        var action = "getExtraEquipment";
        var param = {action:action,cid:cid};
        return this.mainAjaxRequest(param);
    },
    getCarConsumingMaterials : function (cmid){
        var action = "getCarConsumingMaterials";
        var param = {action:action,cmid:cmid};
        return this.mainAjaxRequest(param);
    },
    getEnterExitCarInfo : function (eeID){
        var action = "getEnterExitCarInfo";
        var param = {action:action,eeID:eeID};
        return this.mainAjaxRequest(param);
    },
    //+++++++++++++++++++++++ ثبت وقایع انتظامات ++++++++++++++++++++++++++++
    getEventsInfo : function (infoID){
        var action = "getEventsInfo";
        var param = {action:action,infoID:infoID};
        return this.mainAjaxRequest(param);
    },
    //+++++++++++++++++++++++ مدیریت آژانس ها ++++++++++++++++++++++++++++
    getAgencyInfo : function (aid){
        var action = "getAgencyInfo";
        var param = {action:action,aid:aid};
        return this.mainAjaxRequest(param);
    },
    getAttachedAgencyFile : function (aid){
        var action = "getAttachedAgencyFile";
        var param = {action:action,aid:aid};
        return this.mainAjaxRequest(param);
    },
    //+++++++++++++++++++++++ رستوران ها و غذاها و نوشیدنی ها و مسیرها ++++++++++++++++++++++++++++
    getRestaurantInfo : function (rid){
        var action = "getRestaurantInfo";
        var param = {action:action,rid:rid};
        return this.mainAjaxRequest(param);
    },
    getFoodInfo : function (fid){
        var action = "getFoodInfo";
        var param = {action:action,fid:fid};
        return this.mainAjaxRequest(param);
    },
    getDrinkInfo : function (did){
        var action = "getDrinkInfo";
        var param = {action:action,did:did};
        return this.mainAjaxRequest(param);
    },
    getServiceRouteInfo : function (srid){
        var action = "getServiceRouteInfo";
        var param = {action:action,srid:srid};
        return this.mainAjaxRequest(param);
    },
    personnelOfUnit : function(unit){
        var action = "personnelOfUnit";
        var param = {action:action,unit:unit};
        return this.mainAjaxRequest(param);
    },
    //+++++++++++++++++++++++ مدیریت پروژه ها ++++++++++++++++++++++++++++
    getProjectInfo : function (pid){
        var action = "getProjectInfo";
        var param = {action:action,pid:pid};
        return this.mainAjaxRequest(param);
    },
    getProjectWorkflowFile : function (pid){
        var action = "getProjectWorkflowFile";
        var param = {action:action,pid:pid};
        return this.mainAjaxRequest(param);
    },
    getProjectFieldsFile : function (pid){
        var action = "getProjectFieldsFile";
        var param = {action:action,pid:pid};
        return this.mainAjaxRequest(param);
    },
    getProjectWorkflowInfo : function (pid){
        var action = "getProjectWorkflowInfo";
        var param = {action:action,pid:pid};
        return this.mainAjaxRequest(param);
    },
    getProjectWorkflowComment : function (pwid){
        var action = "getProjectWorkflowComment";
        var param = {action:action,pwid:pwid};
        return this.mainAjaxRequest(param);
    },
    getProjectFieldsInfo : function (pid){
        var action = "getProjectFieldsInfo";
        var param = {action:action,pid:pid};
        return this.mainAjaxRequest(param);
    },
    getProjectFieldsComment : function (pwid){
        var action = "getProjectFieldsComment";
        var param = {action:action,pwid:pwid};
        return this.mainAjaxRequest(param);
    },
    //+++++++++++++++++++++++ مدیریت بودجه ها ++++++++++++++++++++++++++++
    getBudgetInfo : function (bid){
        var action = "getBudgetInfo";
        var param = {action:action,bid:bid};
        return this.mainAjaxRequest(param);
    },
    getBudgetComponentsInfo : function (bcid){
        var action = "getBudgetComponentsInfo";
        var param = {action:action,bcid:bcid};
        return this.mainAjaxRequest(param);
    },
    getAttachedBudgetFile : function (bid){
        var action = "getAttachedBudgetFile";
        var param = {action:action,bid:bid};
        return this.mainAjaxRequest(param);
    },
    getPlanningComment : function (bcdid){
        var action = "getPlanningComment";
        var param = {action:action,bcdid:bcdid};
        return this.mainAjaxRequest(param);
    },
    getProductionComment : function (bcdid){
        var action = "getProductionComment";
        var param = {action:action,bcdid:bcdid};
        return this.mainAjaxRequest(param);
    },
    yearBudgetComponents : function (bid){
        var action = "yearBudgetComponents";
        var param = {action:action,bid:bid};
        return this.mainAjaxRequest(param);
    },
    getOutProgramBudgetComment : function (opbid){
        var action = "getOutProgramBudgetComment";
        var param = {action:action,opbid:opbid};
        return this.mainAjaxRequest(param);
    },
    getDisplacementBudgetComment : function (dbid){
        var action = "getDisplacementBudgetComment";
        var param = {action:action,dbid:dbid};
        return this.mainAjaxRequest(param);
    },
    getDelayBudgetComment : function (dbid){
        var action = "getDelayBudgetComment";
        var param = {action:action,dbid:dbid};
        return this.mainAjaxRequest(param);
    },
    dateBudgetComponents : function (bDate){
        var action = "dateBudgetComponents";
        var param = {action:action,bDate:bDate};
        return this.mainAjaxRequest(param);
    },
    getOutProgramBudgetInfo : function (opbID){
        var action = "getOutProgramBudgetInfo";
        var param = {action:action,opbID:opbID};
        return this.mainAjaxRequest(param);
    },
    getDisplacementBudgetInfo : function (dbID){
        var action = "getDisplacementBudgetInfo";
        var param = {action:action,dbID:dbID};
        return this.mainAjaxRequest(param);
    },
    getDelayBudgetInfo : function (dbID){
        var action = "getDelayBudgetInfo";
        var param = {action:action,dbID:dbID};
        return this.mainAjaxRequest(param);
    },
    checkValidationDate : function (){
        var action = "checkValidationDate";
        var param = {action:action};
        return this.mainAjaxRequest(param);
    },
    getValidationMonth : function (bDate){
        var action = "getValidationMonth";
        var param = {action:action,bDate:bDate};
        return this.mainAjaxRequest(param);
    },
    getAmendmentBudgetComment : function (abid){
        var action = "getAmendmentBudgetComment";
        var param = {action:action,abid:abid};
        return this.mainAjaxRequest(param);
    },
    getAmendmentBudgetInfo : function (abid){
        var action = "getAmendmentBudgetInfo";
        var param = {action:action,abid:abid};
        return this.mainAjaxRequest(param);
    },
    yearBudgetDisplacementComponents : function (bid){
        var action = "yearBudgetDisplacementComponents";
        var param = {action:action,bid:bid};
        return this.mainAjaxRequest(param);
    },
    getMonthOfThisYear : function (bid){
        var action = "getMonthOfThisYear";
        var param = {action:action,bid:bid};
        return this.mainAjaxRequest(param);
    },
    //+++++++++++++++++++++++ ارتباط با پرسنل ++++++++++++++++++++++++++++
    getContactToPersonnelInfo : function (cid){
        var action = "getContactToPersonnelInfo";
        var param = {action:action,cid:cid};
        return this.mainAjaxRequest(param);
    },
    //+++++++++++++++++++++++ مدیریت جلسات ++++++++++++++++++++++++++++
    getMeetingGroupInfo : function (mid){
        var action = "getMeetingGroupInfo";
        var param = {action:action,mid:mid};
        return this.mainAjaxRequest(param);
    },
    getSubstituteMembers : function (members){
        var action = "getSubstituteMembers";
        var param = {action:action,members:members};
        return this.mainAjaxRequest(param);
    },
    getFirstMeetingComments : function (fmID){
        var action = "getFirstMeetingComments";
        var param = {action:action,fmID:fmID};
        return this.mainAjaxRequest(param);
    },
    getSubstituteUsers : function (fmID){
        var action = "getSubstituteUsers";
        var param = {action:action,fmID:fmID};
        return this.mainAjaxRequest(param);
    },
    getFirstMeetingInfo : function (fmID){
        var action = "getFirstMeetingInfo";
        var param = {action:action,fmID:fmID};
        return this.mainAjaxRequest(param);
    },
    getMeetingMembers : function (fmID){
        var action = "getMeetingMembers";
        var param = {action:action,fmID:fmID};
        return this.mainAjaxRequest(param);
    },
    getAllowedMembers : function (fmID){
        var action = "getAllowedMembers";
        var param = {action:action,fmID:fmID};
        return this.mainAjaxRequest(param);
    },
    checkMeetingCommentStatus : function (fmID){
        var action = "checkMeetingCommentStatus";
        var param = {action:action,fmID:fmID};
        return this.mainAjaxRequest(param);
    },
    getMeetingMembersAndJobs : function (fmID){
        var action = "getMeetingMembersAndJobs";
        var param = {action:action,fmID:fmID};
        return this.mainAjaxRequest(param);
    },
    getMeetingWorkReportHtm : function (jobID){
        var action = "getMeetingWorkReportHtm";
        var param = {action:action,jobID:jobID};
        return this.mainAjaxRequest(param);
    },
    getMeetingWorkReportHtm1 : function (jobID){
        var action = "getMeetingWorkReportHtm1";
        var param = {action:action,jobID:jobID};
        return this.mainAjaxRequest(param);
    },
    getMeetingJobPercent : function (jobID){
        var action = "getMeetingJobPercent";
        var param = {action:action,jobID:jobID};
        return this.mainAjaxRequest(param);
    },
    getMeetingWorkReportCommentHtm : function (mwrID){
        var action = "getMeetingWorkReportCommentHtm";
        var param = {action:action,mwrID:mwrID};
        return this.mainAjaxRequest(param);
    },
    //+++++++++++++++++++++++ مدیریت برچسب ها ++++++++++++++++++++++++++++
    getChangeRequestLabelDesc : function (lid){
        var action = "getChangeRequestLabelDesc";
        var param = {action:action,lid:lid};
        return this.mainAjaxRequest(param);
    },
    getLabelInfo : function (lid){
        var action = "getLabelInfo";
        var param = {action:action,lid:lid};
        return this.mainAjaxRequest(param);
    },
    getAttachedLabelFile : function (lid){
        var action = "getAttachedLabelFile";
        var param = {action:action,lid:lid};
        return this.mainAjaxRequest(param);
    },
    getLabelRequestInfo : function (lrid){
        var action = "getLabelRequestInfo";
        var param = {action:action,lrid:lrid};
        return this.mainAjaxRequest(param);
    },
    getAttachedLabelRequestFile : function (lrid){
        var action = "getAttachedLabelRequestFile";
        var param = {action:action,lrid:lrid};
        return this.mainAjaxRequest(param);
    },
    getLabelRequestDetails : function (lrid){
        var action = "getLabelRequestDetails";
        var param = {action:action,lrid:lrid};
        return this.mainAjaxRequest(param);
    },
    getRenderingRequestInfo : function (rid){
        var action = "getRenderingRequestInfo";
        var param = {action:action,rid:rid};
        return this.mainAjaxRequest(param);
    },
    checkRenderingRequest : function (piece){
        var action = "checkRenderingRequest";
        var param = {action:action,piece:piece};
        return this.mainAjaxRequest(param);
    },
    getAttachedRenderingRequestFile : function (rid){
        var action = "getAttachedRenderingRequestFile";
        var param = {action:action,rid:rid};
        return this.mainAjaxRequest(param);
    },
    //+++++++++++++++++++++++ مدیریت برنامه دستگاه ها ++++++++++++++++++++++++++++
    getAttachedDeviceProgramFile : function (dpID){
        var action = "getAttachedDeviceProgramFile";
        var param = {action:action,dpID:dpID};
        return this.mainAjaxRequest(param);
    },
    getDeviceInfo : function (did){
        var action = "getDeviceInfo";
        var param = {action:action,did:did};
        return this.mainAjaxRequest(param);
    },
    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    mainAjaxRequest : function (formData,atype,url){
        if(typeof atype == "undefined"){
                var atype="POST";
        }
        if(typeof url == "undefined"){
                var url = "php/managemantproccess.php";
        }
        var ajaxResult = "";
        $.ajax({
            url:url,
            type:atype,
            async: false,
            data : formData, 
        //     beforeSend: function(){
        //         console.log('Mainbefore..'+formData.action);
        //         add_loading(formData.action);
        //    },
        //    complete:function(){
        //     console.log('Maincompleted..'+formData.action);
        //     setTimeout(function(){remove_loading(formData.action)},500)
              
        //    },
            success:function(res){
                if(res=="access denied"){
                       window.open("login.php","_self"); 
                       exit;
                    }
                    var result = new Array();
                    result = JSON.parse(res);
                    if(result['res'] == "false"){
                        notice1Sec(result['data'],'yellow');
                        ajaxResult = false;
                    }else if(result['res'] == "true"){
                        ajaxResult = result['data'];
                    }               
            }
        });
        return ajaxResult;
    }
};



