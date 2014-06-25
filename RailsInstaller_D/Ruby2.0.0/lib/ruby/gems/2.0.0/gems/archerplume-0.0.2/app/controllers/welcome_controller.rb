 require'google/api_client'
class WelcomeController < ApplicationController

  def new
    
  end
  def index
        
  client = Google::APIClient.new
    client.authorization.client_id = '422061338001-fp3q5ts40hfhcb1vkjfoeugs1hpfplas.apps.googleusercontent.com'

    client.authorization.client_secret = '422061338001-fp3q5ts40hfhcb1vkjfoeugs1hpfplas@developer.gserviceaccount.com'
    client.authorization.redirect_uri = 'http://archercraftstore.github.io/'
    client.authorization.scope = 'https://www.googleapis.com/auth/drive.file'
    redirect_to client.authorization.authorization_uri
    authorizatin_code = params[:code]
    client.code = authorization_code
    client.fetch_access_token!
    drive = client.discovered_api('drive','v2')
  end
end
