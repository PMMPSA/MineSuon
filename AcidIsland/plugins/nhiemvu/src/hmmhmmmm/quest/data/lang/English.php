<?php

namespace hmmhmmmm\quest\data\lang;

class English{

   public static function init(): array{
      return [
         "reset" => false,
         "version" => 1,
         "notfound.plugin" => "§cThis plugin will not work, Please install the plugin %1",
         "notfound.libraries" => "§cLibraries %1 not found, Please download this plugin to as .phar",
         "plugininfo.name" => "§fName plugin %1",
         "plugininfo.version" => "§fVersion %1",
         "plugininfo.author" => "§fList of creators %1",
         "plugininfo.description" => "§fDescription of the plugin. §eis a plugin free. If you redistribute it, please credit the creator. :)",
         "plugininfo.facebook" => "§fFacebook %1",
         "plugininfo.youtube" => "§fYoutube %1",
         "plugininfo.website" => "§fWebsite %1",
         "plugininfo.discord" => "§fDiscord %1",
         "playerquest.info.error1" => "§cError: %1",
         "playerquest.info.text1" => "Quest name §b%1",
         "playerquest.info.text2" => "§fAmount already done: §a%1/%2",
         "playerquest.info.text3" => "§fExplain: %1",
         "playerquest.info.text4" => "§fAward: §b%1%2",
         "questdata.info.text1" => "§b%1",
         "questdata.info.text2" => "§fExplain: %1",
         "questdata.info.text3" => "§fAmount to do: §a%1",
         "questdata.info.text4" => "§fAward: §b%1",
         "questmanager.eventlist.breakblock" => "Break Block",
         "questmanager.eventlist.placeblock" => "Place Block",
         "questmanager.eventlist.kill_entity" => "Kill living things",
         "questmanager.eventlist.kill_player" => "Kill players",
         "questmanager.eventlist.trading.text1" => "Trading",
         "questmanager.eventlist.trading.text2" => "Please hold item.",
         "questmanager.eventlist.online" => "Online",
         "questmanager.start.complete" => "§d%1 §fcompleted quest §e%2 §fand has award %3",
         "command.consoleError" => "§cSorry: commands can be typed only in the game.",
         "command.permissionError" => "§cSorry: You cannot type this command.",
         "command.sendHelp.empty" => "§eYou can see more commands by typing /quest help",
         "command.info.usage" => "/quest info",
         "command.info.description" => "§fCredit of the plugin creator",
         "command.list.usage" => "/quest list",
         "command.list.description" => "§fSee all available quests.",
         "command.list.error1" => "§cYou don't have quests",
         "command.remove.usage" => "/quest remove <QuestNname>",
         "command.remove.description" => "§fDelete quest",
         "command.remove.error1" => "§cTry: %1",
         "command.remove.error2" => "§cYou don't have quests",
         "command.remove.error3" => "§cQuest name not found %1",
         "command.remove.complete" => "You have successfully deleted quest %1.!",
         "command.add.usage" => "/quest add <PlayeName> <QuestName>",
         "command.add.description" => "§fAdded quests for players",
         "command.add.error1" => "§cTry: %1",
         "command.add.error2" => "§cPlayer name not found or player not online",
         "command.add.error3" => "§cQuest name not found %1",
         "command.add.error4" => "§cYou have completed this quest.",
         "command.add.error5" => "§cNeed to use items to exchange. %1",
         "command.add.complete1" => "Player §b%1 §freceived quest §a%2",
         "command.add.complete2" => "You have received quest §a%1",
         "command.data.usage" => "/quest data create|remove|list|setlimit|addlimit",
         "command.data.error1" => "§cTry: %1",
         "command.data.event.usage" => "/quest data event",
         "command.data.event.description" => "§fSee various events",
         "command.data.event.complete" => "§fEvent §b%1 §f%2",
         "command.data.create.usage" => "/quest data create <NameTheQuest> <Max> <Event> <DescriptiveText> <AwardMessage> <CommandAward>",
         "command.data.create.description" => "§fCreate a quest",
         "command.data.create.error1" => "§cTry: %1",
         "command.data.create.error2" => "§cQuest name already exists.",
         "command.data.create.error3" => "§c<Max> Please write as numbers.",
         "command.data.create.error4" => "§cEvent not found %1",
         "command.data.create.error5" => "§cPlease hold item.",
         "command.data.create.complete1" => "Quest §a%1 §fevent §b%2 (%3) §fhas created!",
         "command.data.create.complete2" => "Quest §a%1 §fevent §b%2 (%3) §fhas created! By using item §d%4",
         "command.data.remove.usage" => "/quest data remove <Quest name>",
         "command.data.remove.description" => "§fDelete quest",
         "command.data.remove.error1" => "§cTry: %1",
         "command.data.remove.error2" => "§cQuest name not found %1",
         "command.data.remove.complete" => "Successfully removed quest %1!",
         "command.data.list.usage" => "/quest data list",
         "command.data.list.description" => "§fSee a list of quests.",
         "command.data.list.error1" => "§cNo quests",
         "command.data.list.complete" => "List of all quests §b%1",
         "command.data.setlimit.usage" => "/quest data setlimit <QuestName>",
         "command.data.setlimit.description" => "§fRestricted to players only completing this quest once",
         "command.data.setlimit.error1" => "§cTry: %1",
         "command.data.setlimit.error2" => "§cQuest name not found %1",
         "command.data.setlimit.complete" => "Quest §a%1 §fsuccessfully set limit",
         "command.data.addlimit.usage" => "/quest data addlimit <QuestName> <PlayerName>",
         "command.data.addlimit.description" => "§fAdd players' names to that limited quest.",
         "command.data.addlimit.error1" => "§cTry: %1",
         "command.data.addlimit.error2" => "§cQuest name not found %1",
         "command.data.addlimit.error3" => "§cThis quest not limited",
         "command.data.addlimit.error4" => "§cThis player name already exists.",
         "command.data.addlimit.complete" => "Quest §a%1 §fhas add player §e%2 §fto limited quests",
         "command.data.slapper_get.usage" => "/quest data slapper_get <QuestName>",
         "command.data.slapper_get.description" => "§fCreate a slapper add quest to player",
         "command.data.slapper_get.error1" => "§cTry: %1",
         "command.data.slapper_get.error2" => "§cQuest name not found %1",
         "form.menu.button1" => "§edata",
         "form.menu.button2" => "§fYou have all (§a%1§f) quest",
         "form.menu.error1" => "§cAn error has occurred\n§eYou are not allowed to use",
         "form.menu.error2" => "§cAn error has occurred\n§eYou don't have quests",
         "form.edit.button1" => "§c[Delete quest]",
         "form.remove.content" => "§fAre you sure? To remove quests §a%1",
         "form.remove.button1" => "§aOk",
         "form.remove.button2" => "§cCancel",
         "form.data.menu.button1" => "§aCreate a quest",
         "form.data.event.content" => "§ePlease select an event.",
         "form.data.create.input1" => "§eNameTheQuest",
         "form.data.create.input2" => "§eMax",
         "form.data.create.input3" => "§eDescriptive Text",
         "form.data.create.input4" => "§eAward Message",
         "form.data.create.input5" => "§eCommand Award",
         "form.data.create.error1" => "§cAn error has occurred\n§e<QuestName> Please enter the name of the quest.",
         "form.data.create.error2" => "§cAn error has occurred\n§e<QuestName> Quest name %1 already exists",
         "form.data.create.error3" => "§cAn error has occurred\n§e<Max> Required and please write in numbers",
         "form.data.create.error4" => "§cAn error has occurred\n§e<DescriptiveText> Need to put",
         "form.data.create.error5" => "§cAn error has occurred\n§e<AwardMessage> Need to put",
         "form.data.create.error6" => "§cAn error has occurred\n§e<CommandAward> Need to put",
         "form.data.create.error7" => "§cAn error has occurred\n§ePlease hold item.",
         "form.data.edit.button1" => "§fEdit quest",
         "form.data.edit.button2" => "§fLimit player do complete this quest once.",
         "form.data.edit.button3" => "§fReset limit",
         "form.data.edit.button4" => "§fDelete limit",
         "form.data.edit.button5" => "§fCreate a slapper add quest to player",
         "form.data.edit.button6" => "§c[Delete quest]",
         "form.data.edit.resetlimit.complete" => "Quest §a%1 §fhas reset limit successfully",
         "form.data.edit.unlimit.complete" => "Quest §a%1 §fhas delete limit successfully",
         "questutils.makeslapper.complete" => "§aCreate a slapepr successfully!"
      ];
   }
   
}