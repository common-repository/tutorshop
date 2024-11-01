array_value = Object.keys(array_value).map((key) => [Number(key), array_value[key]]);
             for(var i =0; i < array_value.length;i++){ array_value[i] = array_value[i][1]};
             
             function returnCellString(count,type,content ="")
             {
                 var id,name,value
                 var value = content
                 var domType = 'text'
                 var domClass = 'regular-text'
                if(type == 'start_date'){domType='datetime';domClass=''}
                 var og = "tutorshop_options[livestreams]["+count+"]["+type+"]"
                 var inputString = "<input type ="+domType+" id ='"+og+"'name='"+og+"'value='"+value+"' class = '"+domClass+"'/>"
                if(type =='button_delete')
                {
                    inputString = "<input type = 'button' onClick= 'removeLine("+count+")' value = 'X'/>"
                }
                if(type =='platform')
                {
                    inputString  = "<select id ='"+og+"'name='"+og+"'value='"+value+"'>"
                    for(var i = 0; i < streamingPlatforms.length;i++)
                    {
                        inputString = inputString+"<option"
                        if(streamingPlatforms[i].value == value){
                            inputString = inputString+" selected"
                        }
                        inputString = inputString+" value ='"+streamingPlatforms[i].value+"'>"+streamingPlatforms[i].name+"</option>"
                    }
                    inputString = inputString+"</select>"
                }
                if(type == 'cat_ID')
                {
                    inputString  = "<select id ='"+og+"'name='"+og+"'value='"+value+"'>"
                    for(var i = 0; i < productCategories.length;i++)
                    {
                        inputString = inputString+"<option"
                        if(productCategories[i].slug == value){
                            inputString = inputString+" selected"
                        }
                        inputString = inputString+" value ='"+productCategories[i].slug+"'>"+productCategories[i].name+"</option>"
                    }
                    inputString = inputString+"</select>"
                }
                return inputString
                        
            } 
             
             function createElementFromHTML(htmlString,id=null) {
                    var div = document.createElement('td');
                    div.innerHTML = htmlString;
                    if(id != null)
                    {
                        div.id = id
                    }
                    return div;
                }
             function addNewLine(lineObject = null,index=null)
             {
                var tableElement = document.getElementById('LIVE_TABLE');
                document.getElementById('LIVE_TABLE').style.display = 'table';
                var rowElement = document.createElement("TR")
                var newLineObject;
                if(lineObject == null)
                {
                     newLineObject = 
                { 
                    "name":"New LiveStream",
                    "url":"youtube.com",
                    "start_date": "1969-04-20 13:00:00",
                    "platform": "twitch",
                    "cat_ID": 0
                }
                    array_value.push(newLineObject)
                    console.log("line object is null, adding new to list")
                }
                else
                {
                    newLineObject = lineObject
                    if(newLineObject.platform == null){newLineObject.platform = 'twitch'}
                    if(newLineObject.cat_ID == null){newLineObject.cat_ID = 0}
                    console.log("old line re-created")
                }
                var length = array_value.length-1
                if(index != null){length = index}
                rowElement.appendChild(createElementFromHTML(returnCellString(length,'name',newLineObject.name)))
                rowElement.appendChild(createElementFromHTML(returnCellString(length,'platform',newLineObject.platform)))
                rowElement.appendChild(createElementFromHTML(returnCellString(length,'url',newLineObject.url)))
                rowElement.appendChild(createElementFromHTML(returnCellString(length,'cat_ID',newLineObject.slug)))
                rowElement.appendChild(createElementFromHTML(returnCellString(length,'start_date',newLineObject.start_date)))
                rowElement.appendChild(createElementFromHTML(returnCellString(length,'button_delete')))
                
                tableElement.getElementsByTagName("tbody")[0].appendChild(rowElement)
               console.log(array_value)

             }

             function removeLine(lineIndex) {
                
                array_value.splice(lineIndex,1);
                var lines = document.getElementById('LIVE_TABLE').getElementsByTagName("tr")
                var header = lines[0]
                var tbody = document.getElementById('LIVE_TABLE').getElementsByTagName("tbody")[0]
                tbody.innerHTML = ''
                tbody.append(header)
                for(var i = 0; i < array_value.length;i++)
                {
                   addNewLine(array_value[i],i)
                }

                console.log(array_value)

             }
